<?php

namespace App\Livewire\Tracking;

use Carbon\Carbon;
use Livewire\Component;
use App\Repositories\ApiRepository;
use App\Repositories\TrackingRepository;

class Tracking extends Component
{
    public $trackingNumber;
    public $error = null;
    public $trackingDetails;
    public $order;
    public $lastStatusCode;
    public $brazilStatusCode = null;
    
    public function render()
    {
        return view('livewire.tracking.tracking');
    }
    
    public function trackOrder(){

        $this->error = null;
        $trackingNumber = trim($this->trackingNumber);
        $trackingRepository = new TrackingRepository;
        $this->order = $trackingRepository->getOrder($trackingNumber);
        if($this->order){
            $apiRepository = new ApiRepository; 
            $response = $apiRepository->get('/api/v1/order/tracking/' . $trackingNumber)->json();
            if($response['success']==false){
                $this->error = "Server/API Error: " .$response['message'];
            }
            $this->trackingDetails = $response["data"];

            if(isset($this->trackingDetails->data->apiTrackings)){
                $this->brazilStatusCode = $this->BrazilStatus($this->trackingDetails['apiTrackings'],$this->trackingDetails['hdTrackings']);
            }

            $this->lastStatusCode = $this->getLastStatusCode($this->trackingDetails['hdTrackings']);
            
        }else{
            $this->error = "Invalid Tracking Number or Order not found";
        }
        $this->trackingNumber= null;
    }
    private function getLastStatusCode($hdDeliveryStatusCode)
    {
        $statusCode = end($hdDeliveryStatusCode);
        return $statusCode['status_code'];
    }

    private function BrazilStatus($tracking, $hdTrackings)
    {
        if ($tracking->codigo == 'PAR') {
            return 90;
        }

        if ($tracking->codigo == 'PAR') {
            return 100;
        }

        if ($tracking->codigo == 'DO' || $tracking->codigo == 'RO') {
            return 110;
        }

        if ($tracking->codigo == 'OEC') {
            return 120;
        }

        if ($tracking->codigo == 'BDEBDIBDR' || $tracking->codigo == 'BDI') {
            return 130;
        }

        if ($tracking->codigo == 'BDE') {
            return 140;
        }

        if ($tracking->codigo == 'PO') {
            $lastTracking = $hdTrackings->last();

            $todayDate = date('Y-m-d');
            $lastTrackingDate = $lastTracking->created_at;

            $difference = Carbon::parse($todayDate)->diffInDays(Carbon::parse($lastTrackingDate));
            
            if ($difference > 2 && optional(optional(optional($tracking)->unidade)->endereco)->cidade) {
                return 140;
            }

            return 90;
        }
    }
}
