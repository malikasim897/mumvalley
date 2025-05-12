<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use App\Models\StorageInvoice;
use App\Services\Excel\Export\ExportInvoice;
use App\Repositories\StorageInvoiceRepository;

class StorageInvoiceController extends Controller
{
    protected $storageInvoiceRepository;
    public function __construct(StorageInvoiceRepository $storageInvoiceRepository)
    {
        $this->storageInvoiceRepository = $storageInvoiceRepository;
        // $this->middleware('permission:invoice.edit', ['only' => ['show']]);
        // $this->middleware('permission:invoice.view', ['only' => ['index']]);
    }

    public function index() {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();
        
        return view('storage-invoices.index', compact('users'));
    }

    public function show($id){
        $invoice = Invoice::find($id);
        return view('storage-invoices.show', compact('invoice'));
    }

    public function exportInvoice(Request $request){
        $exportUsers = new ExportInvoice(
            $this->storageInvoiceRepository->getExportInvoice($request)
        );
        return $exportUsers->handle();

    }

    public function generateInvoices(Request $request)
    {
        try {
            // Validate the Invoice Request
            $request->validate([
                'user' => 'required',
                'year' => 'required',
                'month' => 'required',
            ], [
                'user.required' => 'The user name field is required.',
                'year.required' => 'The year field is required.',
                'month.required' => 'The month field is required.',
            ]);

            // Call the repository function to generate invoices
            $response = $this->storageInvoiceRepository->generateInvoices($request);

            // Check if response contains an error or success message
            if (isset($response['error'])) {
                // Log the error message
                \Log::error('Invoice generation error: ' . $response['error']);
                // Redirect back with an error message
                return redirect()->back()->withInput()->with('error', $response['error']);
            }

            if (isset($response['message'])) {
                // Handle success message
                return redirect()->back()->withInput()->with('success', $response['message']);
            }

            // Default case if the response doesn't contain expected keys
            return redirect()->back()->withInput()->with('error', 'Unexpected response from invoice generation.');

        } catch (\Exception $e) {
            // Handle any exceptions that might occur
            $errorMessage = $e->getMessage();
            \Log::error('An exception occurred: ' . $errorMessage);
            return back()->withInput()->with('error', 'An error occurred: ' . $errorMessage);
        }
    }


    public function destroy($id)
    {
        try {
            $invoice = StorageInvoice::findOrFail($id);

            // Delete the invoice
            $invoice->delete();

            return redirect()->back()->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getStorageInvoice($id, $type)
    {
        $invoice = StorageInvoice::find($id);
        $view = view('orders.partials.render_storage_invoice', ['invoice' => $invoice])->render();
        return response()->json(['view' => $view]);
    }
}
