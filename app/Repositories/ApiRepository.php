<?php

namespace App\Repositories;

use App\Exceptions\APIExceptionHandler;
use App\Models\Setting;
use App\Traits\AuthUser;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class ApiRepository
{
    protected $baseUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->baseUrl = Config::get('api.base_url');
        $this->accessToken = Config::get('api.access_token');
    }

    protected function getRequestHeaders()
    {
        // Get the user's access token from the settings table
        $userToken = Setting::select('token','mode')->where("isActive",1)->first();
        $this->baseUrl = $userToken->mode == "live" ? env('API_LIVE_URL') : $this->baseUrl;
         
        return [
            'Authorization' => 'Bearer ' .$userToken->token,
            'Accept' => 'application/json',
        ];
    }

    public function get($endpoint, $params = [])
    {
        $this->getRequestHeaders();
    //    try{
            return Http::withHeaders($this->getRequestHeaders())->withOptions(['verify' => false])
            ->get($this->baseUrl . $endpoint, $params);
    //    }catch(Exception $e){
    //        dd($e);
    //         return new APIExceptionHandler($e->getMessage());
    //    }
    }

    public function post($endpoint, $data = [])
    {
        $this->getRequestHeaders();        
            return Http::withHeaders($this->getRequestHeaders())->withOptions(['verify' => false])
            ->post($this->baseUrl . $endpoint, $data);
    }

    public function put($endpoint, $data = [])
    {
        $this->getRequestHeaders();
       try{
            return Http::withHeaders($this->getRequestHeaders())->withOptions(['verify' => false])
            ->put($this->baseUrl . $endpoint, $data);
       }catch(Exception $e){
            return new APIExceptionHandler($e->getMessage());
       }
    }

    public function delete($endpoint, $params = [])
    {
        $this->getRequestHeaders();

        // try{
            return Http::withHeaders($this->getRequestHeaders())->withOptions(['verify' => false])
            ->delete($this->baseUrl . $endpoint, $params);
        // }catch(Exception $e){
        //     return new APIExceptionHandler($e->getMessage());
        // }
    }
}
