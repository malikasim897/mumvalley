<?php

namespace App\Repositories;

use DB;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WarehouseProduct;
use App\Models\OrderCustomerDetail;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\DocumentRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class TransactionRepository
{
    protected $apiRepository;


    public function delete($id)
    {
        $order = Order::with('images')->findOrFail($id);
        if ($order->parcel_id) {
            $this->apiRepository->delete('/api/v1/parcels/' . $order->parcel_id)->json();
        }
        return $order->delete();
    }

    public function getOrderCount()
    {
        $query = Order::where('status', 'order');

        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', Auth::id());
        }

        return $query->count();
    }

    public function getTodayOrderCount()
    {
        return $this->getUserOrderQuery()
            ->whereDate('created_at', today())
            ->count();
    }

    public function getCurrentMonthOrderCount()
    {
        return $this->getUserOrderQuery()
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function getCurrentYearCount()
    {
        return $this->getUserOrderQuery()
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function getSince2020Count()
    {
        return $this->getUserOrderQuery()
            ->whereDate('created_at', '>=', '2020-01-01')
            ->count();
    }

    private function getUserOrderQuery()
    {
        return Order::when(auth()->user()->hasRole('user'),function($query) {
            return $query->where('user_id', Auth::id());
        });
    }
        
    public function getExportOrder($request)
    {
        $start = $request->started." 00:00:00";
        $end = $request->ended." 23:59:59";
        $orderData = Order::when(auth()->user()->hasRole('user'),function($query){
                          return $query->where('user_id',Auth::user()->id);
                        })->wherebetween('created_at',[$start ,$end])->get();
        return $orderData;
    }

    public function getOrderByIds(array $ids)
    {
        $query = Order::query();

        return $query->whereIn('id',$ids)->get();
    }

}
