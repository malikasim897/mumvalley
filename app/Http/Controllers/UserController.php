<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use App\Repositories\ApiRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use App\Services\Excel\Export\ExportUsers;

class UserController extends Controller
{

    protected $userRepository, $apiRepository;

    public function __construct(UserRepository $userRepository, ApiRepository $apiRepository)
    {
        $this->userRepository = $userRepository;
        $this->apiRepository = $apiRepository;
        $this->middleware('permission:user.view|user.create|user.edit|user.delete', ['only' => ['index','store']]);
        $this->middleware('permission:user.create', ['only' => ['create','store']]);
        $this->middleware('permission:user.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = $this->userRepository->get();
        return view('user.index');
    }

    /**
     * how the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('user.create', compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'min:0', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        $user = new User;
        $latestUser = User::orderBy('id', 'desc')->first()->id;
        
        $user->name = $request->name;
        $user->po_box_number =  substr( $request->name, 0, 1 ).str_pad( $latestUser + 1 , 3, '0', STR_PAD_LEFT );
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->password = bcrypt($request->password);
        $user->assignRole('user');
        $user->save();

        event(new Registered($user));

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
        $user->setting()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'address'    => $address,
            'country_id' => $countryId,
            'state'      => $stateId,
            'city'       => $city,
            'zipcode'    => $zipcode
        ]);

        if ($user) {
            return redirect()->route('users.index')->with('success', 'Customer added successfully');
        } else {
            return redirect()->route('users.index')->with('error', 'Customers not created! Something went wrong');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = $this->userRepository->getUser($id);
        $states = null;
        // if ($user->setting != null) {
        //     $response = $this->apiRepository->get('/api/v1/country/' . $user->setting->country_id . '/states');
        //     $states =  $response->json();
        // }
        $roles = $this->userRepository->getRoles();
        $countries = Country::all();

        return view('user.edit', compact('user', 'roles', 'countries', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userRepository->update($request, $id);

        if ($user) {
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } else {
            return redirect()->route('users.index')->with('error', 'User not updated! Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->userRepository->delete($id)) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } else {
            return redirect()->route('users.index')->with('error', 'Something went wrong.');
        }
    }

    public function getStates($countryId)
    {
        $sates = $this->apiRepository->get('/api/v1/country/' . $countryId . '/states');
        return $sates->json();
    }

    public function impersonate(User $user)
    {
        if ($this->userRepository->impersonate($user)) {
            return redirect('dashboard');
        } else {
            return redirect()->route('users.index')->with('error', 'Something went wrong.');
        }
    }

    public function stopImpersonate()
    {
        if ($this->userRepository->stopImpersonate()) {
            return redirect('users');
        } else {
            return redirect()->route('dashboard')->with('error', 'Something went wrong.');
        }
    }

    public function exportUser()
    {
        $exportUsers = new ExportUsers(
            $this->userRepository->getExportUser()
        );
        return $exportUsers->handle();
    }


    public function updateUserRates(Request $request) {

        $user = $this->userRepository->userRates($request);

        if ($user) {
            return redirect()->back()->with('success', 'User rates updated successfully');
        } else {
            return redirect()->back()->with('error', 'User rates not updated! Something went wrong');
        }
    }
}
