<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

use Illuminate\Support\Str;
class TokenGenerator extends Component
{    
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }
    public function render()
    {
        return view('livewire.token-generator',[
            'user' => User::find($this->userId)
        ]);
    }

    public function revoke()
    { 
        User::find($this->userId)->update([
            'api_token' => md5(microtime()).'-'.Str::random(116).'-'.md5(microtime())
        ]);

    }
    function selectedUsers() {
        $user = User::find($this->userId);
        $user->update([
            'api_enabled' => !$user->api_enabled,
        ]);
    }

}