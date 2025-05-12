<?php

namespace App\Repositories;


use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentInvoiceRepository
{
    public function get(array $filters, $paginate = true, $pageSize = 50, $orderBy = 'id', $orderType = 'asc')
    {
        $query = PaymentInvoice::query();

        // Apply filter for paid by user (admin check)
        if (!Auth::user()->hasRole('admin')) {
            $query->where('paid_by', Auth::id());
        }

        // Apply filters for user details
        if (!empty($filters['user'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['user']}%")
                    ->orWhere('email', 'LIKE', "%{$filters['user']}%");
            });
        }

        // Apply filter for payment type
        if (!empty($filters['payment_type'])) {
            $query->where('payment_type', $filters['payment_type']);
        }

        // Apply filter for payment status
        if ($filters['is_paid'] !== null && $filters['is_paid'] !== '') {
            $query->where('is_paid', $filters['is_paid']);
        }

        // Apply filter for UUID
        if (!empty($filters['uuid'])) {
            $query->where('uuid', 'LIKE', "%{$filters['uuid']}%");
        }

        // Apply filter for total amount
        if (!empty($filters['total_amount'])) {
            $query->where('total_amount', 'LIKE', "%{$filters['total_amount']}%");
        }

        // Apply filter for order count
        if (!empty($filters['order_count'])) {
            $query->where('order_count', 'LIKE', "%{$filters['order_count']}%");
        }

        // Apply date filters
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'], 
                \Carbon\Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        }


        // Apply sorting
        $query->orderBy($orderBy, $orderType);

        // Calculate totals before paginating
        $totalInvoices = $query->count();
        $paidInvoices = (clone $query)->where('is_paid', 1)->count();
        $partialPaidInvoices = (clone $query)->where('partial_paid', 1)->count();
        $unpaidInvoices = ($totalInvoices) - ($partialPaidInvoices) - ($paidInvoices);

        // Paginate or return all results
        $invoices = $paginate ? $query->paginate($pageSize) : $query->get();

        return [
            'invoices' => $invoices,
            'totalInvoices' => $totalInvoices,
            'paidInvoices' => $paidInvoices,
            'unpaidInvoices' => $unpaidInvoices,
            'partialPaidInvoices' => $partialPaidInvoices
        ];
    }

    public function getExportInvoice($request){
            $start = $request->started." 00:00:00";
            $end = $request->ended." 23:59:59";
            $invoiceData = PaymentInvoice::where("user_id",$request->userId)->wherebetween('created_at',[$start ,$end])->with("user")->get();
            return $invoiceData;
    }

    public function getUnpaidOrders()
    {
        $orders = Order::query()
                        ->where('payment_status', false)
                        ->where('order_status', 'in_process')
                        ->where('total_amount','>',0)
                        ->doesntHave('paymentInvoices')
                        ->orderBy('id', 'desc');
        
        return $orders->get();
    }

    public function createInvoice(Request $request)
    {
        $orders = (new OrderRepository)->getOrderByIds($request->get('orders',[]));
        $orders = collect($orders->filter(function($order){
            return !$order->getPaymentInvoice();
        })->all());

        DB::beginTransaction();
        
        try {

            $invoice = PaymentInvoice::create([
                'uuid' => PaymentInvoice::generateUUID(),
                'paid_by' => $orders->first()->user_id,
                'order_count' => $orders->count(),
            ]);

            DB::commit();

            $invoice->orders()->sync($orders->pluck('id')->toArray());

            $invoice->update([
                'total_amount' => $invoice->orders()->sum('total_amount'),
            ]);

            return $invoice;
           
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
       
    }

    public function createPaymentInvoice(Request $request)
    {
        $encryptedOrders = $request->orders ?? [];
        $decryptedIds = collect($encryptedOrders)->map(fn($id) => decrypt($id));
        $orders = (new OrderRepository)->getOrderByIds($decryptedIds->toArray());
        $orders = collect($orders->filter(function($order){
            return !$order->getPaymentInvoice();
        })->all());

        DB::beginTransaction();
        
        try {

            $invoice = PaymentInvoice::create([
                'uuid' => PaymentInvoice::generateUUID(),
                'paid_by' => $orders->first()->user_id,
                'order_count' => $orders->count(),
            ]);

            DB::commit();

            $invoice->orders()->sync($orders->pluck('id')->toArray());

            $invoice->update([
                'total_amount' => $invoice->orders()->sum('total_amount'),
            ]);

            foreach ($orders as $order) {
                $order->update(['order_status' => 'delivered']);
            }

            return $invoice;
           
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
       
    }

    public function updatePaymentInvoice(Request $request, $invoice)
    {
        $orders = Order::where('id', $request->order_id)->get();
        
        $orders = collect($orders->filter(function($order) use($invoice){
            return !$order->getPaymentInvoice() || $order->getPaymentInvoice()->id === $invoice->id;
        })->all());

        DB::beginTransaction();

        try {
            $invoice->orders()->sync($orders->pluck('id')->toArray());

            $invoice->update([
                'total_amount' => $invoice->orders()->sum('total_amount'),
                'order_count' => $invoice->orders()->count()
            ]);

            foreach ($orders as $order) {
                $order->update(['order_status' => 'delivered']);
            }
            
            DB::commit();
            return $invoice;
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->withInput()->with('error', 'An error occurred: ' . $ex->getMessage());
        }
        
    }

    public function updateInvoice(Request $request,PaymentInvoice $invoice)
    {
        $orders = Order::find($request->get('orders',[]));
        
        $orders = collect($orders->filter(function($order) use($invoice){
            return !$order->getPaymentInvoice() || $order->getPaymentInvoice()->id === $invoice->id;
        })->all());

        DB::beginTransaction();

        try {
            $invoice->orders()->sync($orders->pluck('id')->toArray());

            $invoice->update([
                'total_amount' => $invoice->orders()->sum('total_amount'),
                'order_count' => $invoice->orders()->count()
            ]);
            
            DB::commit();
            return $invoice;
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->withInput()->with('error', 'An error occurred: ' . $ex->getMessage());
        }
        
    }
}
