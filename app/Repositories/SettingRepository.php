<?php

namespace App\Repositories;

use DB;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

class SettingRepository
{
    public function store($request)
    {


       $dataPresent = Setting::where("user_id",Auth::user()->id)->where("mode",$request->mode_type)->first();
        if(isset($dataPresent)){
            $dataPresent->token = $request->token;
            $dataPresent->isActive = 1;
            $dataPresent->save();
            $this->deActiveToken($request->mode_type);
            return true;
        }else{
            $changeMode =  $request->mode_type == "live" ? "dev" : "live";
            $dataPresent = Setting::where("user_id",Auth::user()->id)->whereIn("mode",["live","dev"])->first();
            if(isset($dataPresent)){
                $activeRecord = $dataPresent->isActive == 1 ? 0 : 1; 
            }else{
                $activeRecord=1;
            }   
            $mergeDataToRequest = [
                'mode'=>$request->mode_type,
                'isActive' => $activeRecord,
                "user_id"=>Auth::user()->id
              ];
           $request->merge($mergeDataToRequest);
           Setting::create($request->all());
           return true;
        }
        
    }

    public function getToken()
    {
        return Setting::where('user_id', Auth::user()->id)
                        ->where("isActive",1)   
                        ->first();
    }
    
    public function updateToken($activeMode)
    {
       $activeModeData = Setting::where("mode",$activeMode)->first();
       
       if(isset($activeModeData))
       {
            $activeModeData->isActive = 1;
            $activeModeData->save();
           
            $this->deActiveToken($activeMode);
            return response()->json(compact("activeModeData"));          
       }else{

        return response()->json(["message"=>"showElemet"]);
       }
    }

    private function deActiveToken($activeMode)
    {
        
        $deactiveMode =  $activeMode == "live" ? "dev" : "live";
        $deActiveModeData = Setting::where("mode",$deactiveMode)->first();
        if(isset($deActiveModeData)){
               $deActiveModeData->isActive = 0;
               $deActiveModeData->save();
        }
    }
   
}
