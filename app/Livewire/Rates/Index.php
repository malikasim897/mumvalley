<?php

namespace App\Livewire\Rates;

use App\Models\ShippingRate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
    
            $rates = ShippingRate::where(function($query){
                $query->where('shipping_service_id', 'like', '%' . $this->search . '%')
                    ->orWhere('user_id', 'like', '%' . $this->search . '%')
                    ->orWhere('shipping_service_name', 'like', '%' . $this->search . '%')
                    ->orWhere('service_subclass', 'like', '%' . $this->search . '%')
                    ->orWhereHas("user",function($query){
                        return $query->where("name", 'like', '%' . $this->search . '%');
                    });
            });
            
            if (auth()->user()->hasRole('user')) {
                $rates =  $rates->where('user_id', auth()->user()->id);
            }
         $rates =   $rates->orderBy('id', 'desc')->paginate(10);

        return view('livewire.rates.index', ['rates' => $rates]);
    }
}
