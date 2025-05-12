<?php

namespace App\Repositories;

use Exception;
use App\Models\Rate;
use App\Models\User;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use App\Models\ShippingService;

use App\Http\Requests\RateRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Excel\Import\ImportRates;
use App\Services\Excel\ImportCharges\ImportCourierExpressRates;

class RateRepository
{

    public function store(RateRequest $request)
    {
        try {
            $file = $request->file('csv_file');
            $shippingService = explode(',', $request->shipping_service_id);
            $serviceId = $shippingService[0];
            $serviceName = $shippingService[1];
            $serviceCode = $shippingService[2];
            $importService = new ImportRates($file, $request->user_id, $serviceId, $serviceName, $serviceCode);
            $importService->handle();
            return redirect()->route('rates.index')->with('success', 'Rates upload successfully');
        } catch (Exception $exception) {
            return back()->with('error', 'Error while Saving Rate: ' . $exception->getMessage());
        }
    }

    public function getWeightRates($weight, $userId)
    {

        $shippingRates = ShippingRate::where('user_id', $userId)->whereHas('datas')->with('datas')->where('active', 1)->get();
        $rates = null;
        foreach ($shippingRates as $shippingRate) {
            $data = $shippingRate->datas()->where('weight_in_gram', '=', $weight)->orderBy('id', 'asc')->first();
            if(empty($data)){
                $data = $shippingRate->datas()->where('weight_in_gram', '>', $weight)->orderBy('id', 'asc')->first();
                if(isset($data)){
                    $data = $shippingRate->datas()->where('id', '=', --$data->id)->orderBy('id', 'asc')->first();
                }
            }
            if(isset($data->value)){
                $rates[] = [
                    "id" => $shippingRate->shipping_service_id,
                    "shippingServices" => $shippingRate->shipping_service_name,
                    "Weight" => ($weight / 1000) . " kg/cm",
                    "cost" => $data->value,
                ];
            }
        }
        return [
            'success' => true,
            'message' => $rates ? sizeof($rates) : 0 . " services rate found against your Weight",
            "data" => $rates,
        ];
    }
}
