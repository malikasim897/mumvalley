<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\InvoiceOrder;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use App\Models\StorageInvoice;
use Illuminate\Support\Facades\DB;
use App\Repositories\ApiRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\ProductRepository;
use App\Services\Excel\Export\ExportOrder;
use App\Http\Requests\Parcel\ParcelRequest;

class TransactionController extends Controller
{

    protected $productRepository;
    protected $orderRepository;
    protected $apiRepository;

    function __construct(
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        ApiRepository $apiRepository
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->apiRepository = $apiRepository;
        $this->middleware('permission:order.view|order.create|order.edit|order.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:order.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    public function confirmPayment($id, $type)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->status = 'succeeded';
            $transaction->save();

            if($type === 'payment') {
                // Find the corresponding invoice
                $invoice = PaymentInvoice::findOrFail($transaction->invoice_id);

                // Update the payment status and payment type of all related orders
                $invoice->orders()->update([
                    'payment_status' => true,
                    'payment_type' => 'direct_transfer',
                ]);
            } elseif($type === 'storage') {
                $invoice = StorageInvoice::findOrFail($transaction->invoice_id);
            }

            // Update the invoice status
            $invoice->update([
                'is_paid' => true,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Failed to confirm payment.']);
        }
    }

    public function declinePayment($id, $type)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->status = 'declined';
            $transaction->invoice_status = 'cancelled';
            $transaction->save();

            if($type === 'payment') {
                // Find the corresponding invoice
                $invoice = PaymentInvoice::findOrFail($transaction->invoice_id);
            } elseif($type === 'storage') {
                $invoice = StorageInvoice::findOrFail($transaction->invoice_id);
            }

            // Update the invoice status
            $invoice->update([
                'cancelled' => true,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Failed to decline payment.']);
        }
    }

    public function shipOrders($id)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Find the transaction by ID
            $transaction = Transaction::findOrFail($id);

            // Update the transaction's invoice status to shipped
            $transaction->invoice_status = 'shipped';
            $transaction->save();

            // Update all related orders' statuses to shipped
            $orders = $transaction->invoice->orders;
            foreach ($orders as $order) {
                $order->order_status = 'shipped';
                $order->save();
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Orders shipped successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error shipping orders: ' . $e->getMessage());

            return redirect()->route('transactions.index')->with('error', 'An error occurred while shipping orders.');
        }
    }

    public function viewInvoice($id, $type)
    {
        if ($type === 'payment') {
            $invoice = PaymentInvoice::find($id);
            $view = view('orders.partials.render_invoice_view', ['invoice' => $invoice])->render();

        } else if ($type === 'storage') {
            $invoice = StorageInvoice::find($id);
            $view = view('orders.partials.render_storage_invoice', ['invoice' => $invoice])->render();
        }

        return response()->json(['view' => $view]);
    }

    public function exportOrder(Request $request)
    {
        $exportUsers = new ExportOrder(
            $this->orderRepository->getExportOrder($request)
        );
        return $exportUsers->handle();
    }
}
