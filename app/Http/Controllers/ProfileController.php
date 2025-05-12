<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Repositories\ApiRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{

    protected $apiRepository , $profileRepository;

    public function __construct(ApiRepository $apiRepository, ProfileRepository $profileRepository)
    {
        $this->apiRepository = $apiRepository;
        $this->profileRepository = $profileRepository;
        $this->middleware('permission:profile.view|profile.create|profile.edit|profile.delete', ['only' => ['index','store']]);
        $this->middleware('permission:profile.create', ['only' => ['create','store']]);
        $this->middleware('permission:profile.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:profile.delete', ['only' => ['destroy']]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $this->profileRepository->getUser($request->user()->id);
        $countries = Country::all();
        $states = null;

        return view('profile.edit', compact('user', 'countries', 'states'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ProfileRepository $profileRepository): RedirectResponse
    {
        $profileRepository->update($request);
        return Redirect::route('profile.edit')->with('success', 'profile updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
        ]);

        $user = $request->user();
        if (Hash::check($request->input('current_password'), $user->password)) {

            Auth::logout();
            $user->status = 0;
            $user->update();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return Redirect::to('/');

        } else {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect password.']);
        }
    }

    public function getStates($countryId)
    {
        $sates = $this->apiRepository->get('/api/v1/country/' . $countryId . '/states');
        return $sates->json();
    }
}
