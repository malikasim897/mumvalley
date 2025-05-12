<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\User;
use App\Models\UserSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use DB;

class UserRepository
{
    public function get()
    {
        return User::all()->except(Auth::id());
    }

    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function getCountries()
    {
        return Country::all();
    }


    public function update($request, $id)
    {
        $user = User::findOrFail($id);
        
        // Sync roles
        $user->syncRoles($request->role);

        // Parse name if first_name or last_name not provided
        $fullName = $user->name;
        $nameParts = explode(' ', trim($fullName), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Apply fallbacks if fields are missing
        $firstName = $request->first_name ?? $firstName;
        $lastName = $request->last_name ?? $lastName;
        $address = $request->address ?? 'Kucha Khuh Road Abdul Hakim';
        $countryId = $request->country_id ?? 167; // Pakistan
        $stateId = $request->state ?? 'Punjab';
        $city = $request->city ?? 'Abdul Hakim';
        $zipcode = $request->zipcode ?? '58180';

        // Update user
        $user->update($request->all());

        // Update or create settings
        $newUser = $user->setting()->updateOrCreate([
            'user_id' => $id,
        ], [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'address'    => $address,
            'country_id' => $countryId,
            'state'      => $stateId,
            'city'       => $city,
            'zipcode'    => $zipcode
        ]);

        if ($newUser) {
            return true;
        }
    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return true;
    }

    public function impersonate(User $user)
    {
        session(['adminId' => auth()->id()]);
        auth()->login($user);
        return true;
    }

    public function stopImpersonate()
    {
        auth()->logout();
        auth()->loginUsingId(session('adminId'));
        session()->forget('adminId');
        return true;
    }

    public function getExportUser()
    {
        return User::all();
    }

    public function userRates($request)
    {
        $user = User::findOrFail($request->user_id);

        $user->userRates()->create([
            'fnsku_price'    => $request->fnsku_price,
            'bubblewrap_price'    => $request->bubblewrap_price,
            'polybag_price'    => $request->polybag_price,
            'small_box_price'    => $request->small_box_price,
            'medium_box_price'    => $request->medium_box_price,
            'large_box_price'    => $request->large_box_price,
            'additional_units_price'    => $request->additional_units_price,
        ]);

        return true;
    }

}
