<?php

namespace App\Repositories\Api;

use App\Repositories\ParcelRepository;
use App\Repositories\ApiRepository;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Order;
use App\Models\InvoiceOrder;
use App\Models\Deposit;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class LabelRepository
{
    protected $apiRepository;
    protected $parcelRepository;
    function __construct(ParcelRepository $parcelRepository, ApiRepository $apiRepository)
    {
        $this->parcelRepository = $parcelRepository;
        $this->apiRepository = $apiRepository;
    }




    public function label($order)
    {
        if ($order->parcel_id)
            return  $this->printLabel($order);

        DB::beginTransaction();
        $user = $order->user;
        $balance = $user->current_balance;
        $needToPay = $order->need_to_pay;
        if ($balance > $needToPay) {
            if ($needToPay != 0) {
                $paid = $this->payOrder($order);
                if (!$paid) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message', 'Unable to pay the order.'], 422);
                }
            }
        } else {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'User has insufficient balance. Please add balance!'], 422);
        }
        $createParams = $this->parcelRepository->setOrderParams($order->id);
        $registorOrder = $this->apiRepository->post('/api/v1/parcels', $createParams)->json();
        if ($this->isValidApiResponse($registorOrder)) {
            $order->update([
                'parcel_id' => $registorOrder['data']['id'],
                'wr_number' => $registorOrder['data']['warehouse_number'],
                'tracking_code' => $registorOrder['data']['tracking_code'],
            ]);
            DB::commit();
            return  $this->printLabel($order);
        } else {
            DB::rollBack();
            return  response()->json([
                'success' => false,
                'message' => "Server Error:" . $registorOrder['message'],
                'errors' => $registorOrder['errors']
            ], 500);
        }
    }
    function printLabel($order)
    {
        try {

            if ($order->tracking_code) {
                return  $this->getLabelData($order);
            }
            $label = $this->apiRepository->get('/api/v1/parcel/' . $order->parcel_id . '/cn23')->json();
            // dd($label); 
            //order have to update
            if ($this->isValidApiResponse($label)) {
                $order->update([
                    'label_url' => $label['data']['url'],
                    'tracking_code' => $label['data']['tracking_code'],
                ]);
                return $this->getLabelData($order);
            } else {
                return  response()->json([
                    'success' => false,
                    'message' =>  "Server Error:" . $label['message'],
                ], 500);
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return  response()->json([
                'message' => 'An error occurred: ' . $errorMessage,
            ], 422);
        }
    }
    private function isValidApiResponse(array $response): bool
    {
        return isset($response['success']) && $response['success'] === true;
    }
    function payOrder($order)
    {
        $user = $order->user;
        $balance = $user->current_balance;
        $needToPay = $order->need_to_pay;
        if ($balance > $needToPay && $needToPay != 0 || $needToPay < 0) {

            $invoice = Invoice::create([
                'amount' =>  abs($needToPay),
                'user_id' =>  Auth::id(),
            ]);

            InvoiceOrder::create([
                'amount'              =>  abs($needToPay),
                'order_id'            =>  $order->id,
                'invoice_id'          =>  $invoice->id,
            ]);

            Deposit::create([
                'user_id'           => $user->id,
                'uuid'              => uniqid(),
                'balance'           => $balance - $needToPay,
                'amount'            => abs($needToPay),
                'is_credit'         => $needToPay < 0 ? true : false,
                'last_four_digits'  => 'FromBalance',
                'depositable_type'  => get_class($invoice),
                'depositable_id'    => $invoice->id,
            ]);

            $order->update([
                'status' => 'payment_done',
            ]);
            return true;
        } else {
            return false;
        }
    }
    function getLabelData($order)
    {
        return response()->json([
            'success' => true,
            'message' => 'label generated successfully.',
            'data' => [
                'label_url' => $order->label_url,
                'tracking_code' => $order->tracking_code,
            ]
        ], 201);
    }
}
