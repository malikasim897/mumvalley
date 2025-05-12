<?php

namespace App\Repositories;

use DB;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

class TrackingRepository
{
    public function getOrder($id)
    {
        return Order::where('tracking_code' ,$id)
                      ->where("tracking_code","<>","") ->first();
    }
}
