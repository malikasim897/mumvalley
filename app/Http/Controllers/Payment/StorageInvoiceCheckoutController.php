<?php

namespace App\Http\Controllers\Payment;

use Stripe\Stripe;
use App\Models\Country;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\StorageInvoice;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\OrderCheckoutRepository;
use App\Services\PaymentServices\AuthorizeNetService;

class StorageInvoiceCheckoutController extends Controller
{
    public function index($id)
    {
        $userCountry = null;

        $invoiceId = Crypt::decrypt($id);
        $invoice = StorageInvoice::find($invoiceId);
        
        if( $invoice->isPaid()){
            session()->flash('alert-danger','Invoice already paid.');
            return view('storage-invoices.index');
        }

        $stripeKey = null;

        $paymentGateway = null;
        $balance = null;
        $notEnoughBalance = null;

        if(optional($invoice->user->setting)->country_id) {
            $userCountry = Country::where('id', $invoice->user->setting->country_id)->value('name');
        }
        
        return view('storage-invoices.checkout',compact('invoice', 'paymentGateway', 'stripeKey','notEnoughBalance','balance', 'userCountry'));
    }

    public function createStorageInvoicePaymentIntent(Request $request)
    {
        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Retrieve the amount from the request and multiply by 100 to convert pounds to pence
            $amount = $request->amount * 100;
            $name = $request->input('name');
            $invoiceId = $request->input('id');
            $email = $request->input('email');

            // Create the Payment Intent with the dynamic amount and GBP currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,  // Amount in pence
                'currency' => 'gbp',  // Set currency to GBP
                'payment_method_types' => ['card'],
                'metadata' => [
                'name' => $name,
                'email' => $email,
                'invoice_id' => $invoiceId,
                ],
            ]);

            // Send the client secret back to the frontend
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handlePaymentReturn(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntentId = $request->query('payment_intent');
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

        // Verify the payment status
        if ($paymentIntent->status === 'succeeded') {
            // Use DB transaction to ensure atomicity
            DB::beginTransaction();

            try {
                $invoice = StorageInvoice::where('uuid', $paymentIntent->metadata->invoice_id)->first();
                
                if($invoice->latestTransaction) {
                    // If a transaction exists, update it
                    $transaction = $invoice->latestTransaction;
                    $transaction->update([
                        'user_id' => Auth::id(),
                        'invoice_id' => $invoice->id,
                        'invoice_type' => StorageInvoice::class,
                        'payment_intent_id' => $paymentIntentId,
                        'latest_charge_id' => $paymentIntent->latest_charge,
                        'payment_method_id' => $paymentIntent->payment_method,
                        'amount' => number_format(($paymentIntent->amount_received) / 100, 2),
                        'currency' => $paymentIntent->currency,
                        'created' => $paymentIntent->created,
                        'transaction_date' => now(),
                        'payment_type' => 'stripe',
                        'payment_for' => 'storage_invoice',
                        'invoice_status' => null,
                        'status' => $paymentIntent->status,
                    ]);
                } else {
                    // Create the transaction
                    $transaction = Transaction::create([
                        'user_id' => Auth::id(),
                        'invoice_id' => $invoice->id,
                        'invoice_type' => StorageInvoice::class,
                        'payment_intent_id' => $paymentIntentId,
                        'latest_charge_id' => $paymentIntent->latest_charge,
                        'payment_method_id' => $paymentIntent->payment_method,
                        'amount' => number_format(($paymentIntent->amount_received) / 100, 2),
                        'currency' => $paymentIntent->currency,
                        'created' => $paymentIntent->created,
                        'transaction_date' => now(),
                        'payment_type' => 'stripe',
                        'payment_for' => 'storage_invoice',
                        'status' => $paymentIntent->status,
                    ]);
                }

                // Update the invoice with the transaction_id and payment details
                $invoice->update([
                    'transaction_id' => $transaction->id,
                    'is_paid' => true,
                    'paid_amount' => number_format(($paymentIntent->amount_received) / 100, 2),
                    'payment_type' => 'stripe',
                    'paid_by' => Auth::id(),
                    'payment_date' => now(),
                    'cancelled' => false,
                ]);

                // Commit the transaction if everything is successful
                DB::commit();

                // Show success message to the user
                return redirect()->route('storage-invoices.index')->with('success', 'Orders payment completed successfully.');

            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs
                DB::rollBack();

                // Log the error (optional)
                \Log::error('Payment processing error: ' . $e->getMessage());

                // Return an error message to the user
                return redirect()->route('storage-invoices.index')->with('error', 'An error occurred while processing the payment. Please try again.');
            }

        } else {
            // Handle payment failure
            return redirect()->route('storage-invoices.index')->with('error', 'Payment failed. Please try again.');
        }
    }

    public function uploadBankReceipt(Request $request)
    {
        try {
            // Validate that the uploaded file is an image
            $request->validate([
                'bankReceipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow only image file types, max 2MB
                'invoice_id' => 'required|exists:storage_invoices,id',
            ]);

            // Find the corresponding invoice
            $invoice = StorageInvoice::findOrFail($request->invoice_id);

            // Define the storage path for receipts
            $storagePath = 'receipts';

            // Generate a unique name for the uploaded file
            $file = $request->file('bankReceipt');
            $extension = $file->getClientOriginalExtension(); // Get the original file extension
            $uniqueFileName = uniqid() . '_' . time() . '.' . $extension; // Create a unique file name
            
            // Store the file with the unique name in 'storage/app/public/receipts'
            $filePath = $file->storeAs($storagePath, $uniqueFileName, 'public');

            if ($invoice->latestTransaction) {
                // If a transaction exists, update it
                $transaction = $invoice->latestTransaction;
                $transaction->update([
                    'user_id' => Auth::id(),
                    'invoice_id' => $invoice->id,
                    'invoice_type' => StorageInvoice::class,
                    'amount' => $invoice->total_amount,
                    'latest_charge_id' => 'ch_' . Str::random(24),
                    'currency' => 'gbp',
                    'transaction_date' => now(),
                    'payment_type' => 'direct_transfer',
                    'payment_for' => 'storage_invoice',
                    'status' => 'pending',
                    'invoice_status' => null,
                    'payment_receipt' => basename($filePath),
                ]);
            } else {
                // Create a new transaction
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_id' => $invoice->id,
                    'invoice_type' => StorageInvoice::class,
                    'amount' => $invoice->total_amount,
                    'latest_charge_id' => 'ch_' . Str::random(24),
                    'currency' => 'gbp',
                    'transaction_date' => now(),
                    'payment_type' => 'direct_transfer',
                    'payment_for' => 'storage_invoice',
                    'status' => 'pending',
                    'payment_receipt' => basename($filePath), // Store only the filename
                ]);
            }

            // Update the invoice with the transaction_id and payment details
            $invoice->update([
                'transaction_id' => $transaction->id,
                'paid_amount' => $invoice->total_amount,
                'payment_receipt' => $transaction->payment_receipt,
                'payment_type' => 'direct_transfer',
                'paid_by' => Auth::id(),
                'payment_date' => now(),
                'cancelled' => false,
            ]);

            // Return success message
            return redirect()->route('storage-invoices.index')->with('success', 'Your receipt has been uploaded successfully. After approval, the invoice will be marked as paid.');

        } catch (\Exception $e) {
            // Return error message
            // Log the error (optional)
            \Log::error('Receipt Payment processing error: ' . $e->getMessage());
            return redirect()->route('storage-invoices.index')->with('error', 'Payment failed. Please try again.');
        }
    }

}
