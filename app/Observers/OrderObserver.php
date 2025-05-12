<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\ShippingRate;
use App\Repositories\RateRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        Log::info('updateed observer');
        try{
            $rate = null;
            if($rate){
                $order->shippment_value = $rate->value;
                $order->saveQuietly();
            }
        }catch(Exception $e){
            Log::info('OrderObserver:update error:'.$e->getMessage());
        }
        $this->checkPaymentStatus($order);
       
    }

    function checkPaymentStatus(Order $order) {
        if($order->need_to_pay!=0 && $order->status!='cancelled'){
            $order->status = 'payment_pending';
            $order->saveQuietly();
        }
    }
    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
