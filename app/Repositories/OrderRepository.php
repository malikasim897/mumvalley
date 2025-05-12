<?php

namespace App\Repositories;

use DB;
use App\Models\User;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\PaymentInvoice;
use App\Models\ProductInventory;
use App\Models\OrderCustomerDetail;
use App\Models\UserProductBalance;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\DocumentRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class OrderRepository
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
        $query = Order::where('order_status', 'in_process');

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

    public function getSince2024Count()
    {
        return $this->getUserOrderQuery()
            ->whereDate('created_at', '>=', '2024-01-01')
            ->count();
    }

    public function gettotalProducts()
    {
        $query = Product::query();
        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        return $query->count();
    }

    public function getpendingInventory()
    {
        $query = ProductInventory::where('status', false);
        if (auth()->user()->hasRole('user')) {
            $query->whereHas('product', function($q) {
                $q->where('user_id', auth()->id());
            });
        }
        return $query->count();
    }

    public function gettotalInProcessOrders()
    {
        $query = Order::where('order_status', 'in_process');

        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        return $query->count();
    }

    public function gettransactionsSucceeded()
    {
        $query = Transaction::where('status', 'succeeded');
        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        return 0;

    }

    public function gettransactionPending()
    {
        $query = Transaction::where('status', 'pending');
        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        return 0;
    }

    public function paidInvoices()
    {
        $query = PaymentInvoice::where('is_paid', true);
        if (auth()->user()->hasRole('user')) {
            $query->where('paid_by', auth()->id());
        }
        return $query->count();
    }

    public function pendingInvoices()
    {
        $query = PaymentInvoice::where('is_paid', false);
        if (auth()->user()->hasRole('user')) {
            $query->where('paid_by', auth()->id());
        }
        return $query->count();
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


    public function updateOrderDetails($request, $orderId)
    {
        $user = Auth::user();
        $order = Order::find($orderId);
        
        // Handle the shipment label upload
        if ($request->hasFile('shipment_label')) {
            $storagePath = 'public/labels';
            $label = $this->updateLabelFile($order, $request, $storagePath);
            $order->update(['shipment_label' => $label]);
        }

        return $order->id;
    }

    public function updateLabelFile(Order $order, $request, $storagePath)
    {
        if ($request->hasFile('shipment_label')) {

            $oldLabel = $order->shipment_label;
            $oldLabelPath = $storagePath . '/' . $oldLabel;
            // Check if the old label exists and delete it
            if ($oldLabel && Storage::exists($oldLabelPath)) {
                Storage::delete($oldLabelPath);
            }

            $file = $request->file('shipment_label');
            $uniqueName = Str::random(32) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($storagePath, $uniqueName);

            return $uniqueName;
        }

        return null; // No file uploaded
    }

    public function getOrderByIds(array $ids)
    {
        $query = Order::query();

        return $query->whereIn('id',$ids)->get();
    }

    public function getTotalUsers() {
        $usersCount = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();
    
        return $usersCount;
    }

    public function getTotalPaidAmount() {
        $paidAmount = PaymentInvoice::sum('paid_amount');
        return number_format($paidAmount, 2);
    }

    public function getTotalRemainingAmount() {
        $totalAmount = PaymentInvoice::sum('total_amount');
        $paidAmount = PaymentInvoice::sum('paid_amount');
        $remainingAmount = number_format($totalAmount - $paidAmount, 2);
        return $remainingAmount;
    }

    public function getTotalPartialPaidInvoices() {
        $partialInvoiceCount = PaymentInvoice::where('partial_paid', true)->count();
        return $partialInvoiceCount;
    }

    public function getTotalReturnableBottles() {
        $latestBalances = UserProductBalance::select('user_id', 'remaining_units')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                      ->from('user_product_balances')
                      ->groupBy('user_id');
            })
            ->get();
    
        $returnableBottles = $latestBalances->sum('remaining_units');
    
        return $returnableBottles;
    }
    
}
