<?php

namespace App\Livewire\Products;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductOrder extends Component
{
    use WithPagination;

    public $productId;
    public $search;
    public $orderPerPage = 50;
    public $start_date;
    public $end_date;

    public $total_confirmed_units = 0;
    public $total_shipped_units = 0;
    public $remaining_units = 0;
    public $total_orders = 0;
    public $status = 'all';

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['generateInvoice'];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function render()
    {
        $user = Auth::user();

        // Retrieve the product
        $product = Product::with('latestConfirmedInventory', 'orderItems')->find($this->productId);

        // Calculate total confirmed units and remaining units from the latest confirmed inventory
        if ($product && $product->latestConfirmedInventory) {
            $this->total_confirmed_units = $product->latestConfirmedInventory->total_units;
            $this->remaining_units = $product->latestConfirmedInventory->remaining_units;
        }

        // Calculate total shipped units by summing all shipped units from order items
        $this->total_shipped_units = $product->orderItems->sum('delivered_units') - $product->orderItems->sum('returned_units');

        // Query to filter orders by product ID, search, and date range
        $query = Order::with(['user', 'paymentInvoices', 'items.product'])
            ->whereHas('items.product', function ($q) {
                $q->where('id', $this->productId); // Filter by product ID
            })
            ->when($this->start_date && $this->end_date, function($query) {
                // Filter by date range if both are set
                $query->whereBetween('date', [$this->start_date, $this->end_date]);
            })
            ->when($this->status !== 'all', function ($query) {
                // Apply the status filter only if status is not 'all'
                $query->where('order_status', $this->status);
            })
            ->where(function ($query) {
                // Apply search filter
                $query->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('items', function($query) {
                        $query->whereHas('product', function($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orWhereHas('items', function($query) {
                        $query->where('delivered_units', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user', function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });

        // If the user has a specific role, restrict query
        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        // Get paginated orders and calculate total orders
        $orders = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);
        $this->total_orders = $orders->total();  // Total orders count

        return view('livewire.products.product-order', compact('orders'));
    }

    // Function to handle invoice creation
    public function generateInvoice()
    {
        // Get orders with status 'shipped' that do not have an associated invoice
        $orders = Order::with('items')
            ->whereHas('items.product', function ($q) {
                $q->where('id', $this->productId); // Filter by product ID
            })
            ->where('order_status', 'shipped')
            ->whereDoesntHave('paymentInvoices') // Only orders without an invoice
            ->when($this->start_date && $this->end_date, function ($query) {
                // Filter by date range if both are set
                $query->whereBetween('date', [$this->start_date, $this->end_date]);
            })
            ->get();
        
        if ($orders->isEmpty()) {
            // Emit event to show error in frontend
            $this->dispatch('invoiceGenerationFailed', [
                'message' => 'No shipped orders without invoices available.'
            ]);
            return;
        }

        DB::beginTransaction();

        try {
            // Create the invoice
            $invoice = PaymentInvoice::create([
                'uuid' => PaymentInvoice::generateUUID(),
                'paid_by' => $orders->first()->user_id,
                'order_count' => $orders->count(),
            ]);

            // Attach orders to the invoice
            $invoice->orders()->sync($orders->pluck('id')->toArray());

            // Calculate the total amount for the invoice
            $totalAmount = 0;
            foreach ($orders as $order) {
                // foreach ($order->items as $item) {
                //     if ($item->product_id == $this->productId) {
                //         $totalAmount += $item->total_price; // Sum the total_price for each relevant product
                //     }
                // }
                $totalAmount += $order->total_amount;
            }

            // Update the invoice with the total amount
            $invoice->update([
                'total_amount' => $totalAmount,
            ]);

            DB::commit();

            // Emit event to show success in frontend
            $this->dispatch('invoiceGenerated', [
                'message' => 'Invoice created successfully.'
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            // Emit event to show error in frontend
            $this->dispatch('invoiceGenerationFailed', [
                'message' => 'Invoice creation failed: ' . $ex->getMessage()
            ]);
        }
    }

}
