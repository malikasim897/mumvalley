<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ApiRepository;

class ApiController extends Controller
{
    protected $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    public function fetchDataFromApi()
    {
        $response = $this->apiRepository->get('/api/v1/products');
        $data = $response->json();
    }
}
