<?php

namespace App\Http\Controllers\Api\PublicApi;

use App\Repositories\Api\APIParcelRepository;
use App\Repositories\Api\LabelRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CreateRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class APIOrderController extends Controller
{
    protected $apiParcelRepository;
    protected $labelRepository;

    function __construct(APIParcelRepository $apiParcelRepository, LabelRepository $labelRepository)
    {
        $this->apiParcelRepository = $apiParcelRepository;
        $this->labelRepository = $labelRepository;
    }

    public function store(CreateRequest $request)
    {
        try {
            return $this->apiParcelRepository->store($request);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return  response()->json(['message' => $errorMessage], 200);
        }
    }
    function label(Order $order)
    {
        try {
            return $this->labelRepository->label($order);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return  response()->json(['message' => $errorMessage], 200);
        }
    }
}
