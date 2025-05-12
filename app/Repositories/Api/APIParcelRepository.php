<?php

namespace App\Repositories\Api;

use DB;
use App\Models\Order;
use App\Models\Recipient;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Repositories\ApiRepository;
use Illuminate\Support\Facades\Log;
use App\Repositories\RateRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Repositories\ParcelRepository;
use App\Http\Resources\OrderItemResource;
use App\Http\Requests\Parcel\ParcelRequest;
use App\Services\Converters\UnitsConverter;
use App\Http\Requests\Api\Order\CreateRequest;
use App\Http\Resources\OrderRecipientResource;
use App\Services\Calculators\WeightCalculator;

class APIParcelRepository
{
    protected $parcelRepository;
    protected $apiRepository;

    function __construct(ParcelRepository $parcelRepository, ApiRepository $apiRepository)
    {
        $this->parcelRepository = $parcelRepository;
        $this->apiRepository = $apiRepository;
    }

    public function store(CreateRequest $request)
    {
        $calculateVolumeWeight = $this->calculateVolumeWeight($request->parcel['weight'], $request->parcel['length'], $request->parcel['width'], $request->parcel['height'], $request->parcel['unit']);
        $calculateOtherWeight =  $this->calculateOtherWeight($request->parcel['weight'], $request->parcel['unit']);

        $data = $request->parcel + ['user_id' => Auth::id(), 'weight_other' => $calculateVolumeWeight, 'vol_weight' => $calculateOtherWeight];
        $selectedShippingService = $this->fetchShippingService($request);
        if (!$selectedShippingService) {
            return  response()->json([
                'message' => 'Service not found.',
            ], 422);
        }
        if (!$selectedShippingService['cost'] || !$selectedShippingService['shippingServices']) {
            return  response()->json([
                'message' => 'No rate found for this volume',
            ], 422);
        }
        $request->items =  array_map(function ($item) {
            return  [
                "sh_code" => $item['sh_code'],
                "description" => $item['description'],
                "quantity" => $item['quantity'],
                "total" => $item['quantity'] * $item['value'],
                "value" => $item['value'],
                "contain_goods" => $item['is_battery'] ? "is_battery" : ($item['is_perfume'] ? 'is_perfume' : ($item['is_flameable'] ? 'is_flameable' : 'no')),
            ];
        }, $request->items);
        $data['service_name'] = $selectedShippingService['shippingServices'];
        $data['shippment_value'] = $selectedShippingService['cost'];
        $data['status'] = 'order';

        return DB::transaction(function () use ($request, $data) {
            $sender = Recipient::create($request->sender);
            $recipient = Recipient::create($request->recipient);
            $order = Order::create($data + ['sender' => $sender->id, 'recipient' => $recipient->id]);
            $this->parcelRepository->storeOrderItems($request->items, $order);
            if ($order) {
                return  response()->json([
                    'message' => 'parcel created',
                    'data' => $order->refresh(),
                ], 200);
            } else {
                return  response()->json([
                    'message' => 'Parcel not created! Something went wrong.',
                ], 200);
            }
        });
    }

    public function calculateVolumeWeight($weight, $length, $width, $height, $unit)
    {
        $actualVolumeWeight = null;
        $weightOther = 0;
        if ($unit == 'kg/cm') {
            $weightOther = UnitsConverter::kgToPound($weight);
            $lengthOther = UnitsConverter::cmToIn($length);
            $widthOther = UnitsConverter::cmToIn($width);
            $heightOther = UnitsConverter::cmToIn($height);
            $currentWeightUnit = 'kg';
            $volumetricWeight = WeightCalculator::getVolumnWeight($length, $width, $height, 'cm');
            $volumeWeight = round($volumetricWeight > $weight ? $volumetricWeight : $weight, 2);

            // if ($discountPercentage && $discountPercentage > 0) {

            //     if ($volumeWeight > $weight) {

            //         if ($discountPercentage == 1) {
            //             $totalDiscountedWeight = $volumeWeight - $weight;
            //             $actualVolumeWeight = $volumeWeight;
            //             return $volumeWeight = $weight;
            //         }

            //     }
            // }
        } else {
            $weightOther = UnitsConverter::poundToKg($weight);
            $lengthOther = UnitsConverter::inToCm($length);
            $widthOther = UnitsConverter::inToCm($width);
            $heightOther = UnitsConverter::inToCm($height);
            $currentWeightUnit = 'lbs';
            $volumetricWeight = WeightCalculator::getVolumnWeight($length, $width, $height, 'in');
            $volumeWeight = round($volumetricWeight > $weight ? $volumetricWeight : $weight, 2);

            // if ($discountPercentage && $discountPercentage > 0) {

            //     if ($volumeWeight > $weight) {

            //         if ($discountPercentage == 1) {
            //             $totalDiscountedWeight = $volumeWeight - $weight;
            //             $actualVolumeWeight = $volumeWeight;
            //             return $volumeWeight = $weight;
            //         }

            //     }
            // }
        }

        return $volumeWeight;
    }
    public function calculateOtherWeight($weight, $unit)
    {
        if ($unit == 'kg/cm') {
            return UnitsConverter::kgToPound($weight);
        } else {
            return UnitsConverter::poundToKg($weight);
        }
    }

    // function fetchShippingService($request)
    // {
    //     // $selectedShippingService = null;
    //     // $rateRepository = new RateRepository();
    //     // $weight_in_gram = $request->parcel['unit'] == "kg/cm" ? $request->parcel['weight'] * 1000 : $request->parcel['weight'];
    //     // $shippingServices = $rateRepository->getWeightRates($weight_in_gram, Auth::id());
    //     // if(empty($shippingServices['data'])){
    //     //     return [
    //     //             "shippingServices" =>  Null,
    //     //             "Weight" => null,
    //     //             "cost" => null,
    //     //         ];
    //     // }
    //     // else{
    //     //     foreach ($shippingServices['data'] as $shippingService) {
    //     //         if ($shippingService["id"] == $request->parcel["service_id"]) {
    //     //             $selectedShippingService = $shippingService;
    //     //             break;
    //     //         }
    //     //     };
    //     // }
    //     // return $selectedShippingService;
    // }

    function fetchShippingService($request)
    {
        $serviceid = $request->parcel['service_id'];
        $userId = Auth::user()->id;
        $selectedShippingService = null;
        $weight_in_gram = $request->parcel['unit'] == "kg/cm" ? $request->parcel['weight'] * 1000 : $request->parcel['weight'];
        $shippingRate = ShippingRate::where('shipping_service_id', $serviceid)->where('user_id', $userId)->whereHas('datas')->with('datas')->where('active', 1)->first();
        if($shippingRate){
            $data = $shippingRate->datas()->where('weight_in_gram', '>=', $weight_in_gram)->orderBy('id', 'asc')->first();
            if($data){
                return [
                    "shippingServices" => $shippingRate->shipping_service_name,
                    "Weight" => ($weight_in_gram / 1000) . " kg/cm",
                    "cost" => $data->value,
                ];
            }
        }
                return $selectedShippingService;
    }







}