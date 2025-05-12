<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Image;
use App\Models\Invoice;
use App\Models\InvoiceOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Recipient;
use App\Models\OrderCustomerDetail;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\DocumentRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class ParcelRepository
{
    protected $countryRepository;
    protected $documnentRepository;
    protected $apiRepository;

    public function __construct(
        DocumentRepository $documnentRepository,
        CountryRepository $countryRepository,
        ApiRepository $apiRepository
    ) {
        $this->documnentRepository = $documnentRepository;
        $this->countryRepository = $countryRepository;
        $this->apiRepository = $apiRepository;
    }

    public function getUser()
    {
        return User::all();
    }

    public function findUser($id)
    {
        return User::findOrFail($id);
    }


    public function store($request, Authenticatable $user)
    {
        $data = $request->all();
        //$data['user_id'] = $user->id;
        // $data['wr_number'] = $this->getWrNumber();
        $order = Order::create($data);
        if (!$order) {
            return false;
        }
        if ($request->hasFile('image')) {
            $storagePath = 'public/images/invoices';
            //$this->uploadImage($order, $request, $storagePath);
            $this->documnentRepository->uploadImage($order, $request, $storagePath);
        }
        return true;
    }

    public function getParcel($id)
    {
        return Order::with('customerSenderDetails', 'customerRecipientDetails', 'orderItems')->find($id);
    }

    public function getRecipient()
    {
        return Recipient::all();
    }

    public function getAddress($id)
    {
        return  Recipient::findOrFail($id);
    }


    public function update($request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return false;
        }
        $order->update($request->all());
        if ($request->hasFile('image')) {
            $storagePath = 'public/images/invoices';
            //$this->uploadImage($order, $request, $storagePath);
            $this->documnentRepository->uploadImage($order, $request, $storagePath);
        }
        return true;
    }


    public function delete($id)
    {

        $order = Order::with('images')->findOrFail($id);
        if (count($order->invoices)) {
            return false;
        }
        if ($order->parcel_id) {
            $this->apiRepository->delete('/api/v1/parcels/' . $order->parcel_id)->json();
        }
        return $order->delete();
    }

    public function deleteOrderItemsByIds($ids)
    {
        if ($ids) {
            $removedItemIds = explode(',', $ids);
            return (OrderItem::whereIn('id', $removedItemIds))->delete();
        }
        return false;
    }

    public function getWrNumber()
    {
        $oldSaleOrder = Order::first();
        $maxId = Order::max('id');
        $newId = 'GMV' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        $newId = substr($newId, 0, 8);
        return $oldSaleOrder !== null ? $newId : 'GMV0001';
    }

    public function storeSenderDetails($request, $orderId)
    {
        $requestData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'address' => $request->address,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'tax_id' => $request->tax_id,
            "user_id" => auth()->user()->id
        ];
        if ($request->country_id) {
            $country = $this->countryRepository->getCountry($request->country_id); // get country from api
            if ($country) {
                $requestData['country_name'] = $country['name'];
                $requestData['country_code'] = $country['code'];
            }
        }
        if ($request->state_id) {
            $state = $this->countryRepository->getState($request->country_id, $request->state_id); // get state against specific country
            if ($state) {
                $requestData['state_name'] = $state['name'];
                $requestData['state_code'] = $state['code'];
            }
        }

        $order = Order::find($orderId);
        if ($order) {
            $customerSenderDetails = $order->customerSenderDetails()->updateOrCreate(
                ['id' => $order->sender],
                $requestData
            );
            $order->update(['sender' => $customerSenderDetails->id]);
        }
        return true;
    }

    public function storeRecipientDetails($request, $orderId)
    {
        $requestData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'address' => $request->address,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'street_no' => $request->street_no,
            'zipcode' => $request->zipcode,
            'tax_id' => $request->tax_id,
            "account_type" => $request->account_type
        ];

        if ($request->country_id) {
            $country = $this->countryRepository->getCountry($request->country_id); // get country from api
            if ($country) {
                $requestData['country_name'] = $country['name'];
                $requestData['country_code'] = $country['code'];
            }
        }
        if ($request->state_id) {
            $state = $this->countryRepository->getState($request->country_id, $request->state_id); // get state against specific country
            if ($state) {
                $requestData['state_name'] = $state['name'];
                $requestData['state_code'] = $state['code'];
            }
        }
        $order = Order::find($orderId);
        if ($order) {
            $customerRecipientDetails = $order->customerRecipientDetails()->updateOrCreate(
                ['id' => $order->recipient],
                $requestData
            );
            $order->update(['recipient' => $customerRecipientDetails->id]);
        }
        return true;
    }

    public function storeShippingItemsDetails($request, $orderId)
    {
            $serviceName = trim($request->service_name);
            if(!$serviceName){
                $shippingRate =  ShippingRate::where('shipping_service_id',$request->service_id)->first();
                $serviceName= optional($shippingRate)->shipping_service_name;
            }
            $order = Order::find($orderId);
            $order->service_id = $request->service_id;
            $order->service_name =$serviceName;
            $order->additional_reference = $request->customer_reference;
            $order->freight_rate = $request->shippment_value;
            $order->tax_modality = $request->tax_modality;
            $order->return_parcel= $request->return_parcel;
        if ($order->status == 'ready') {
            $requestData['status'] = 'order';
        }
        $order->save();
        if ($order && $request->shippingItems) {
            $this->storeOrderItems($request->shippingItems, $order);
        }
        // if the user remove the existing items store against order
        $this->deleteOrderItemsByIds($request->input('removed_item_ids', []));
        return true;
    }

    public function storeOrderItems($items, $order)
    {
        foreach ($items as $item) {
            $itemData = [
                'sh_code' => $item['sh_code'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'value' => $item['value'],
                'total' => $item['total'],
                'contain_goods' => $item['contain_goods'],
            ];
            if (optional($item)['id']) {
                $orderItem = OrderItem::findOrFail($item['id']);
                $orderItem->update($itemData);
            } else {
                $order->orderItems()->create($itemData);
            }
        }
        return true;
    }

    public function setOrderParams($orderId)
    {
        $order = $this->getParcel($orderId);
        $user = $this->findUser($order->user_id);
        $params = [
            'parcel' => [
                'service_id' => $order->service_id,
                'shipment_value' => $order->freight_rate,
                'merchant' => $order->merchant,
                'carrier' => $order->carrier,
                'tracking_id' => $order->tracking_id .rand(0,9999),
                'customer_reference' => $order->additional_reference .rand(0,9999),
                'measurement_unit'   => $order->unit,
                'weight'             => $order->weight,
                'length'             => $order->length,
                'width'              => $order->width,
                'height'             => $order->height,
                'tax_modality'       => $order->tax_modality,
                'return_option'        => $order->return_parcel
            ],
            'sender' => [
                'sender_first_name' => $order->customerSenderDetails->first_name,
                'sender_last_name' => $order->customerSenderDetails->last_name,
                'sender_email' => $order->customerSenderDetails->email,
                'sender_taxId' => $order->customerSenderDetails->tax_id,
            ],
            'recipient' => [
                'first_name' => $order->customerRecipientDetails->first_name,
                'last_name' => $order->customerRecipientDetails->last_name,
                'email' => $order->customerRecipientDetails->email,
                'phone' => $order->customerRecipientDetails->phone,
                'city' => $order->customerRecipientDetails->city,
                "street_no" => $order->customerRecipientDetails->street_no,
                "account_type" => $order->customerRecipientDetails->account_type,
                'address' => $order->customerRecipientDetails->address,
                'address2' => $order->customerRecipientDetails->address_2,
                'tax_id' => $order->customerRecipientDetails->tax_id,
                'zipcode' => $order->customerRecipientDetails->zipcode,
                'state_id' => $order->customerRecipientDetails->state_id,
                'country_id' => $order->customerRecipientDetails->country_id,
            ],
            'products' => [],
        ];

        foreach ($order->orderItems as $item) {
            $is_battery = $is_perfume = $is_flameable = 0;

            if ($item->contain_goods === 'is_battery') {
                $is_battery = 1;
            } elseif ($item->contain_goods === 'is_perfume') {
                $is_perfume = 1;
            } elseif ($item->contain_goods === 'is_flameable') {
                $is_flameable = 1;
            }

            $params['products'][] = [
                'sh_code' => $item->sh_code,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'value' => $item->value,
                'is_battery' => $is_battery,
                'is_perfume' => $is_perfume,
                'is_flameable' => $is_flameable,
            ];
        }
        return $params;
    }

    public function getSenderDetails()
    {
        return Address::query()->where("user_id",auth()->user()->id)->where("tax_id","=",null)->get();
    }

    public function getAddressesList($id)
    {
        return Address::findOrFail($id);
    }
    function refundPaidAmount(Order $order) {
        DB::beginTransaction();
        try {
            $user = $order->user;
            $needToPay = -$order->paid_amount;
            $invoice = Invoice::create([
                'amount' => abs($needToPay),
                'user_id' => Auth::id(),
            ]);
            InvoiceOrder::create([
                'amount' => abs($needToPay),
                'order_id' => $order->id,
                'invoice_id' => $invoice->id,
            ]);
            $balanceAfterRefund = $user->current_balance - $needToPay;
            Deposit::create([
                'user_id' => $user->id,
                'uuid' => uniqid(),
                'balance' => $balanceAfterRefund,
                'amount' => abs($needToPay),
                'is_credit' => $needToPay < 0,
                'last_four_digits' => 'FromBalance',
                'depositable_type' => Invoice::class,
                'depositable_id' => $invoice->id,
            ]);
            $order->update(['status' => 'cancelled']); 
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }    
}
