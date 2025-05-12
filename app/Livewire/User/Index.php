<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination; // Use the WithPagination trait

    public $search;
    protected $paginationTheme = 'bootstrap';
    public $totalUsers;
    public $currentPageNumber=10;

    public function render()
    {

        $users = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('po_box_number', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'asc')
        ->paginate($this->currentPageNumber);
        $this->totalUsers= count(User::all());
        return view('livewire.user.index', compact('users'));
    }
}
