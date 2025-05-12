<?php

namespace App\Livewire\Rates\Edit;

use App\Models\ShippingRate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;
    public $rate;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        return view('livewire.rates.edit.index', ['rate' => $this->rate]);
    }
}