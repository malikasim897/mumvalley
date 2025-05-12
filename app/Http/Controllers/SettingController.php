<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SettingRequest;
use App\Repositories\SettingRepository;

class SettingController extends Controller
{

    protected $settingRepository;


    function __construct(
        SettingRepository $settingRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->middleware('permission:setting.view|setting.create|setting.edit|setting.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:setting.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:setting.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:setting.delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $token = $this->settingRepository->getToken();
        return view('setting.index', compact('token'));
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
    public function store(SettingRequest $request)
    {
        //
        $setting = $this->settingRepository->store($request);
        if($setting)
        {
            return redirect()->route('settings.index')->with('success','Token updated successfully');
        } else {
            return redirect()->route('settings.index')->with('error','Token not Updated! Something went wrong.');
        }
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

    public function GetSettingToken()
    {
        $ActiveToken = $this->settingRepository->getToken();
        if(isset($ActiveToken)){
          return response()->json(compact("ActiveToken"));
        }
        else{
            return response()->json(compact("NotFound"),404);
        }
    }

    public function updateToken($tokenMode)
    {
       return $this->settingRepository->updateToken($tokenMode);
    }
    
}
