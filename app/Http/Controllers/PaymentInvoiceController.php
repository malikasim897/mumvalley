<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Excel\Export\ExportInvoice;
use App\Repositories\PaymentInvoiceRepository;

class PaymentInvoiceController extends Controller
{
    protected $invoiceRepository;
    public function __construct(PaymentInvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        // $this->middleware('permission:invoice.edit', ['only' => ['show']]);
        // $this->middleware('permission:invoice.view', ['only' => ['index']]);
    }

   public function index() {
        return view('payment-invoices.index');
    }

    public function show($id){
        $invoice = Invoice::find($id);
        return view('invoices.show', compact('invoice'));
    }

    public function exportInvoice(Request $request){
        $exportUsers = new ExportInvoice(
            $this->invoiceRepository->getExportInvoice($request)
        );
        return $exportUsers->handle();

    }

    public function create(Request $request) {
        $invoice = $this->invoiceRepository->createPaymentInvoice($request);
        if ($invoice) {
            return redirect()->route('orders.index')->with('success', 'Payment Invoice Created Successfully');
        } else {
            return redirect()->route('orders.index')->with('error', 'Payment Invoice Could Not Created! Something Went Wrong.');
        }
    }

    public function update(PaymentInvoice $paymentInvoice, Request $request) {
        $invoice = $this->invoiceRepository->updatePaymentInvoice($request, $paymentInvoice);
        if ($invoice) {
            return redirect()->route('orders.index')->with('success', 'Payment Invoice Updated Successfully');
        } else {
            return redirect()->route('orders.index')->with('error', 'Payment Invoice Could Not Updated! Something Went Wrong.');
        }
    }

    public function destroy($id)
    {
        try {
            $invoice = PaymentInvoice::findOrFail($id);
            // Detach orders from the invoice
            $invoice->orders()->detach();

            // Delete the invoice
            $invoice->delete();

            return redirect()->back()->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['user', 'items.product', 'items.productInventory'])->findOrFail($id);

        $pdf = PDF::loadView('invoices.show', compact('order'));
        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}
