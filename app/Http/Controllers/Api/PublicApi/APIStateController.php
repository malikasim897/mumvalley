<?php

namespace App\Http\Controllers\Api\PublicApi;

use App\Http\Controllers\Controller;
use App\Repositories\ApiRepository;

class APIStateController extends Controller
{
    protected $apiRepository;
    function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }
    public function index($country_id)
    {
        return $this->apiRepository->get("/api/v1/country/{$country_id}/states")->json();
    }
}
