<?php

namespace App\Livewire\Addresses;

use App\Models\Address;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = Auth::user();

        $addresses = Address::query()->when(auth()->user()->hasRole("user"),function($query){
                return $query->where("user_id",auth()->user()->id);
            })->where(function($query){
               $this->applySearchConditions($query);
            })->where("tax_id","!=",null)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.addresses.index', ['addresses' => $addresses]);
    }

    private function applySearchConditions($query)
    {
        $query->orWhere('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('country_name', 'like', '%' . $this->search . '%')
            ->orWhere('state_code', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->orWhere('tax_id', 'like', '%' . $this->search . '%');
    }
}
