<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Pending extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;
    public $start_date;
    public $end_date;
    protected $paginationTheme = 'bootstrap';

    public $orderPerPage = 50;

    public function mount()
    {
        // Set default values for start_date and end_date
        // $this->start_date = date('Y-m-01');
        // $this->end_date = date('Y-m-d');
    }

    public function render()
    {
        $user = Auth::user();

        // Build the query for orders and their paymentInvoices relationship
        $query = Order::with(['user', 'paymentInvoices', 'items.product']) // Eager load items and their products
            ->where(function ($query) {
                $query->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('items', function ($query) {
                        $query->whereHas('product', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%'); // Search by product name
                        });
                    })
                    ->orWhereHas('items', function ($query) {
                        $query->where('delivered_units', 'like', '%' . $this->search . '%'); // Search by shipped units
                    })
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });

        // Apply date filter
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        // Restrict query if user has a specific role
        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        $query->whereIn('order_status', ['in_process', 'cancelled']);
        // Get paginated orders
        $orders = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);

        return view('livewire.orders.pending', compact('orders'));
    }
}
