<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use function Pest\Laravel\json;
use App\Repositories\ApiRepository;

use App\Http\Requests\TrackingRequest;
use App\Repositories\TrackingRepository;
use Exception;

class TrackingController extends Controller
{
    protected $trackingRepository, $apiRepository;

    public function __construct(TrackingRepository $trackingRepository, ApiRepository $apiRepository)
    {
        $this->trackingRepository = $trackingRepository;
        $this->apiRepository = $apiRepository;
        $this->middleware('permission:tracking.view|tracking.create|tracking.edit|tracking.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:tracking.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tracking.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tracking.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('trackings.index');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function trackOrder(TrackingRequest $request)
    {
        return;      
    }

}
