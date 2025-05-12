<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use App\Repositories\ApiRepository;
use App\Http\Requests\RateRequest;

use App\Repositories\RateRepository;

class RateController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:rate.view', ['only' => ['index', 'store']]);
        $this->middleware('permission:rate.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:rate.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:rate.delete', ['only' => ['destroy']]);
    }
    function index()
    {
        return view('rates.index');
    }

    function create()
    {
        $apiRepository = new  ApiRepository();
        $shippingServices  = $apiRepository->get('/api/v1/user-shipping-services')->json();
        $shippingServices = $shippingServices['data'];
        $countries = Country::all();
        $users = User::all();
        return view('rates.partials.create', ['shippingServices' => $shippingServices, 'countries' => $countries, 'users' => $users]);
    }
    function store(RateRequest $request, RateRepository $repository)
    {
        try {
            return $repository->store($request);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return back()->with('error', 'An error occurred something went wrong: ' . $errorMessage);
        }
        return back()->with('error', 'Order Paid.Now you can register order!');
    }
    function edit($id)
    {
        return view('rates.partials.edit', ['rate' => ShippingRate::with('datas')->find($id)]);
    }
    function destroy($id)
    {
        $rate = ShippingRate::find($id);
        if ($rate->delete()) {
            return back()->with('success', 'Shipping rate deleted successfully!');
        } else {
            return back()->with('error', 'unable to delete shipping services!');
        }
    }
    function changeStatus($id)
    {
        $rate = ShippingRate::find($id);
        if ($rate->update([
            'active' => !$rate->active,
        ])) {
            return back()->with('success', 'Status changed successfully!');
        } else {
            return back()->with('error', 'Unable to change status!');
        }
    }
}
