<?php

namespace App\Repositories;


use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaymentInvoice;
use App\Models\StorageInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StorageInvoiceRepository
{
    public function get(array $filters, $paginate = true, $pageSize = 50, $orderBy = 'id', $orderType = 'asc')
    {
        // Create a new query builder instance for StorageInvoice
        $query = StorageInvoice::query();
        $query->withCount('products');
        

        // Apply filter for paid by user (admin check)
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        // Apply filters for user details
        if (!empty($filters['user'])) {
            $query->whereHas('user', function($query) use ($filters) {
                $query->Where('name', 'LIKE', "%{$filters['user']}%")
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

        // Apply filter for product count
        if (!empty($filters['product_count'])) {
            $query->has('products', '>=', $filters['product_count']);
        }

        // Apply filter for product count
        if (!empty($filters['month'])) {
            $query->where('charge_month', 'LIKE', "%{$filters['month']}%");
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
        $unpaidInvoices = $totalInvoices - $paidInvoices;

        // Paginate or return all results
        $invoices = $paginate ? $query->paginate($pageSize) : $query->get();

        return [
            'invoices' => $invoices,
            'totalInvoices' => $totalInvoices,
            'paidInvoices' => $paidInvoices,
            'unpaidInvoices' => $unpaidInvoices
        ];
    }


    public function getExportInvoice($request){
            $start = $request->started." 00:00:00";
            $end = $request->ended." 23:59:59";
            $invoiceData = StorageInvoice::where("user_id",$request->userId)->wherebetween('created_at',[$start ,$end])->with("user")->get();
            return $invoiceData;
    }

    public function getUnpaidOrders()
    {
        $orders = Order::query()
                        ->where('user_id',Auth::id())
                        ->where('payment_status', false)
                        ->where('order_status', 'in_process')
                        ->where('total_amount','>',0)
                        ->doesntHave('paymentInvoices')
                        ->orderBy('id', 'desc');
        
        return $orders->get();
    }

    public function generateInvoices(Request $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            $year = $request->year ?? date('Y');
            $month = $request->month;

            // Fetch users based on request
            $users = $request->user == 'all' ? 
                User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->get() : User::where('id', $request->user)->get();

            $allInvoicesZero = true; // Flag to track if all invoice amounts are zero or less

            foreach ($users as $user) {
                $invoiceAmount = 0;

                // Check if invoice already exists
                $invoice = StorageInvoice::where('user_id', $user->id)
                    ->where('charge_month', $year . '-' . $month)
                    ->first();

                // Create new invoice if none exists
                if ($invoice && !empty($invoice->paid_by)) {
                    DB::rollBack(); // Rollback transaction
                    return ['error' => "Invoice is already generated and paid for $user->name for the month of $year-$month"];
                } elseif(!$invoice) {
                    $invoice = StorageInvoice::create([
                        'uuid' => StorageInvoice::generateUUID(),
                        'user_id' => $user->id,
                        'charge_month' => $year . '-' . $month,
                        'total_amount' => 0, 
                        'is_paid' => false,
                        'paid_by' => null, 
                    ]);
                }

                foreach ($user->products as $product) {
                    // Get the earliest inventory for the month
                    $firstInventory = $product->lastInventory()
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->oldest('created_at')
                        ->first();

                    if ($firstInventory) {
                        $totalUnits = $firstInventory->remaining_units;
                        $shippedUnits = $product->orderItems()
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->sum('shipped_units');

                        // Calculate storage charges based on percentage limits
                        $storageCharge = $product->latestStorageCharge;
                        $percentageLimit = $storageCharge ? $storageCharge->ship_percentage : 0;
                        $shippedPercentage = number_format(($shippedUnits / $totalUnits) * 100, 2);
                        // dd($shippedPercentage < $percentageLimit, $percentageLimit, $shippedPercentage);
                        if ($shippedPercentage < $percentageLimit) {
                            $storageCharges = $this->calculateStorageCharges(
                                $shippedPercentage, $percentageLimit, 
                                $product, $totalUnits, $shippedUnits
                            );

                            // Update or create invoice product
                            $existingProduct = $invoice->products()
                                ->where('product_id', $product->id)
                                ->first();
                            // dd($existingProduct, !empty($invoice->paid_by));

                            if ($existingProduct && !isset($invoice->paid_by)) {
                                $existingProduct->update([
                                    'percentage_limit' => $percentageLimit,
                                    'shipped_percentage' => $shippedPercentage,
                                    'shipped_units' => $shippedUnits,
                                    'remaining_units' => $totalUnits,
                                    'storage_charges' => $storageCharges,
                                ]);
                            } elseif(!$existingProduct) {
                                $invoice->products()->create([
                                    'product_id' => $product->id,
                                    'percentage_limit' => $percentageLimit,
                                    'shipped_percentage' => $shippedPercentage,
                                    'shipped_units' => $shippedUnits,
                                    'remaining_units' => $totalUnits,
                                    'storage_charges' => $storageCharges,
                                ]);
                            }

                            // Add storage charges to the invoice amount
                            $invoiceAmount += $storageCharges;
                        }
                    }
                }  

                // Debugging statement to check invoice amount
                \Log::info("User {$user->id}: Invoice Amount: {$invoiceAmount}");
                // If invoice amount is greater than 0, mark that not all invoices are zero
                if ($invoiceAmount > 0) {
                    $allInvoicesZero = false;
                    $invoice->update(['total_amount' => $invoiceAmount]);
                } else {
                    // Force delete the invoice if amount is zero
                    $invoice->forceDelete();
                }
            }

            // Check if all invoices had zero or less amount
            if ($allInvoicesZero) {
                DB::rollBack(); // Rollback transaction
                return ['error' => "Invoices cannot be generated, because the user $user->name have completed the month ship percentage limit."];
            }

            // Commit transaction if everything is successful
            DB::commit();
            return ['message' => 'Invoices generated successfully'];

        } catch (\Exception $ex) {
            // Rollback on error
            DB::rollBack();
            return ['error' => $ex->getMessage()];
        }
    }

    private function calculateStorageCharges($shippedPercentage, $percentageLimit, $product, $totalUnits, $shippedUnits)
    {
        // Assuming each product has a storage charge rate
        $chargeRate = $product->latestStorageCharge->storage_charge; // Storage charge rate defined in the product

        // Calculate the difference between the percentage limit and the actual shipped percentage
        $percentageDifference = $percentageLimit - $shippedPercentage;

        // Ensure percentageDifference is not negative
        if ($percentageDifference > 0) {
            // Calculate the remaining units after shipping
            $remainingUnits = $totalUnits - $shippedUnits;

            // Calculate storage charges based on remaining units and percentage difference
            // Formula: remaining_units * (percentage_difference / 100) * charge_rate
            // $storageCharges = $remainingUnits * ($percentageDifference / 100) * $chargeRate;
            $storageCharges = $chargeRate;


            return $storageCharges; // Return the calculated storage charges for this product
        }

        return 0; // No storage charges if the percentage difference is zero or negative
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
