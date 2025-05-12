<?php

namespace App\Repositories;

use DB;
use App\Models\Image;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderCustomerDetail;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\DocumentRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class AddressRepository
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


    public function getAddress($id)
    {
        return Address::findOrFail($id);
    }

    public function getAddresses()
    {
        return Address::where('user_id',auth()->user()->id)->where("tax_id","!=",null)->get();
    }

    public function getAddressesList($id)
    {
        return Address::findOrFail($id);
    }

    public function storeAddressDetails($request, $orderId)
    {
        $order = Order::find($orderId);
        $user = Auth::user();

        $user_id = $user->hasRole('admin') ? $order->user_id : $user->id;
        $requestData = $this->prepareAddressData($request);
        $requestData['user_id'] = $user_id;
        $address = Address::updateOrCreate($requestData,[]);

        if ($address) {
            return true;
        }
        return null;
    }

    public function update($request, $id)
    {
        $requestData = $this->prepareAddressData($request);
        $address = Address::find($id);

        if ($address) {
            $address->update($requestData);
            return true;
        }
        return null;
    }

    private function prepareAddressData($request)
    {
        $requestData = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'address2' => $request->address_2,
            'account_type' => $request->account_type,
            'city' => $request->city,
            'street_no' => $request->street_no,
            'zipcode' => $request->zipcode,
            'tax_id' => $request->tax_id,
            "user_id" => auth()->user()->id,
        ];

        if ($request->country_id) {
            $country = $this->countryRepository->getCountry($request->country_id); // get country from api
            if ($country) {
                $requestData['country_id'] = $country['id'];
                $requestData['country_name'] = $country['name'];
            }
        }

        if ($request->state_id) {
            $state = $this->countryRepository->getState($request->country_id, $request->state_id); // get state against specific country
            if ($state) {
                $requestData['state_id'] = $state['id'];
                $requestData['state_code'] = $state['code'];
            }
        }
        return $requestData;
    }


    public function delete($id)
    {
        return DB::table("addresses")->where('id', $id)->delete();
    }
}
