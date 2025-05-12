<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentInvoice;
use App\Repositories\PaymentInvoiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrdersSelectController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(PaymentInvoice::class);
    }

    public function index(PaymentInvoiceRepository $paymentInvoiceRepository)
    {

        $orders = $paymentInvoiceRepository->getUnpaidOrders();
        return view('payment-invoices.create',compact('orders'));
    }

    public function store(Request $request, PaymentInvoiceRepository $paymentInvoiceRepository)
    {
        if (empty($request->input('orders'))) {
            return redirect()->back()->with('error', 'Please select at least one order to proceed');
        }
        
        $invoice = $paymentInvoiceRepository->createInvoice($request);

        return redirect()->route('payment-invoices.invoice.show', Crypt::encrypt($invoice->id));
    }
}
