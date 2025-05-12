<?php

namespace App\Http\Controllers\Api\PublicApi;

use App\Http\Controllers\Controller;
use App\Repositories\ApiRepository;

class APIShCodeController extends Controller
{
    protected $apiRepository;
    function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }
    public function index()
    {
        return $this->apiRepository->get("/api/v1/shcodes")->json();
    }
}
