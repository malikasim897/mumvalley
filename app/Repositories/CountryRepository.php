<?php

namespace App\Repositories;

use DB;
use App\Repositories\ApiRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CountryRepository
{

    protected $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    public function getCountry(int $countryId)
    {
        $countryResponse = $this->apiRepository->get('/api/v1/countries'); 
        $countries = $countryResponse->json();
        $country = collect($countries)->first(function ($country) use ($countryId) {
            return $country['id'] === $countryId;
        });
        return $country;
    }

    public function getState($countryId,$stateId)
    {
        $response = $this->apiRepository->get('/api/v1/country/'.$countryId.'/states'); // get Specific country states
        $states = $response->json();

        $matchingStates = collect($states)->first(function ($state) use ($stateId) {
            return $stateId == $state['id'];
        });
        return $matchingStates;
    }

}