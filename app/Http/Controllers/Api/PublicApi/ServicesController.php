<?php

namespace App\Http\Controllers\Api\PublicApi;

use App\Http\Controllers\Controller;
use App\Repositories\ApiRepository;
use App\Http\Resources\ShippingRateResource;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    protected $apiRepository;

    function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }
    public function hd_services()
    {
        return $this->apiRepository->get('/api/v1/shipping-services')->json();
    }
    public function user_services()
    {
        return ShippingRateResource::collection(
            ShippingRate::where('user_id', Auth::id())->where('active', true)->get()
        );
    }
}