<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Repositories\ApiRepository;
use App\Http\Requests\AddressRequest;
use App\Repositories\AddressRepository;

class AddressController extends Controller
{

    protected $addressRepository, $apiRepository;


    function __construct(
        AddressRepository $addressRepository,
        ApiRepository $apiRepository
    ) {
        $this->addressRepository = $addressRepository;
        $this->apiRepository = $apiRepository;
        $this->middleware('permission:address.view|address.create|address.edit|address.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:address.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:address.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:address.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('addresses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $address = $this->addressRepository->getAddress($id);
        $addresses = $this->addressRepository->getAddresses();
        $countries = Country::all();

        return view('addresses.edit', compact('address', 'addresses', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, $id)
    {
        
        $user = $this->addressRepository->update($request, $id);

        if ($user) {
            return redirect()->route('addresses.index')->with('success', 'Address updated successfully');
        } else {
            return redirect()->route('addresses.index')->with('error', 'Address not updated! Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->addressRepository->delete($id)) {
            return redirect()->route('addresses.index')->with('success', 'Address deleted successfully');
        } else {
            return redirect()->route('addresses.index')->with('error', 'Something went wrong.');
        }
    }

    public function getAddresses($id)
    {
        // Fetch states based on the selected country ID
        $address = $this->addressRepository->getAddressesList($id);
        return response()->json($address);
    }

    public function getStates($countryId)
    {
        // Fetch states based on the selected country ID
        $response = $this->apiRepository->get('/api/v1/country/' . $countryId . '/states');
        $states = $response->json();
        return response()->json($states);
    }
}
