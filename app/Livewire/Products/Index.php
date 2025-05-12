<?php

namespace App\Livewire\Products;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductInventory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Index extends Component
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
        $query = Product::where(function ($query) {
                $query->where('unique_id', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
                    // ->orWhere('date', 'like', '%' . $this->search . '%')
                    // ->orWhere('dispatched_units', 'like', '%' . $this->search . '%');
            });

        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        // Apply date filter
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        $products = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);

        return view('livewire.products.index', compact('products'));
    }
}
