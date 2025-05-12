<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'min:0', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'agree_to_terms' => ['required', 'accepted']
        ]);

        $user = new User;
        $latestUser = User::orderBy('id', 'desc')->first()->id;

        $user->name = $request->name;
        $user->po_box_number =  substr( $request->name, 0, 1 ).str_pad( $latestUser + 1 , 3, '0', STR_PAD_LEFT );
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = false;

        $user->password = bcrypt($request->password);
        $user->assignRole('user');
        $user->save();

        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}
