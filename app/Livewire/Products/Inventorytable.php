<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductInventory;
use App\Models\WarehouseProduct;

class InventoryTable extends Component
{
    use WithPagination;

    public $product;
    public $search = '';
    public $currentPageNumber = 10;

    protected $queryString = ['search'];

    public function mount($product)
    {
        $this->product = $product;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        
        $inventories = ProductInventory::where('product_id', $this->product->id)
            ->where(function ($query) {
                $query->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('purchased_units', 'like', '%' . $this->search . '%')
                    ->orWhere('unit_price', 'like', '%' . $this->search . '%')
                    ->orWhere('total_price', 'like', '%' . $this->search . '%')
                    ->orWhere('remaining_units', 'like', '%' . $this->search . '%')
                    ->orWhereHas('product', function ($query) {
                        $query->where('unique_id', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->currentPageNumber);

        return view('livewire.products.inventorytable', [
            'inventories' => $inventories,
            'product' => $this->product,
        ]);
    }
}
