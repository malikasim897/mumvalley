<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class index extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;

    protected $paginationTheme = 'bootstrap';
 
    // protected $queryString = ['search'];

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'Desc')
            ->paginate(10);

        return view('livewire.roles.index', ['roles' => $roles]);
    }
}
