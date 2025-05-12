<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\PaymentInvoiceRepository;

class OrdersInvoiceController extends Controller
{

    public function show($id)
    {
        $invoiceId = Crypt::decrypt($id);
        $invoice = PaymentInvoice::find($invoiceId);
        return view('payment-invoices.show',compact('invoice'));
    }

    public function edit($id, PaymentInvoiceRepository $paymentInvoiceRepository)
    {
        $invoiceId = Crypt::decrypt($id);
        $invoice = PaymentInvoice::find($invoiceId);

        $orders = $paymentInvoiceRepository->getUnpaidOrders();
        return view('payment-invoices.edit',compact('invoice','orders'));
    }


    public function update(Request $request, PaymentInvoice $invoice, PaymentInvoiceRepository $paymentInvoiceRepository)
    {
        if (empty($request->input('orders'))) {
            return redirect()->back()->with('error', 'Please select at least one order in the invoice to proceed.');
        }

        $invoice = $paymentInvoiceRepository->updateInvoice($request,$invoice);

        return redirect()->route('payment-invoices.invoice.show', Crypt::encrypt($invoice->id));
    }

}
