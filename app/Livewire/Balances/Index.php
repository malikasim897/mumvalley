<?php

namespace App\Livewire\balances;

use App\Models\Balance;
use App\Models\Deposit;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;
    protected $paginationTheme = 'bootstrap';
    public $orderPerPage=50;
    public function render()
    {
        $query = Deposit::where(function ($query) {
            $query->where('uuid', 'like', '%' . $this->search . '%')
            ->orWhere('last_four_digits', 'like', '%' . $this->search . '%');;
        })->orWhereHas('user', function ($userQuery) {
            $userQuery->where('name', 'like', '%' . $this->search . '%');
        })->when(!Auth::user()->hasRole('admin'),function($query) {
            return $query->where('user_id', Auth::id());
        });

        $deposits = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);
        $users = User::all();
        return view('livewire.balances.index', compact("deposits","users"));
    }
}
