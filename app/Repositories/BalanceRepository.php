<?php

namespace App\Repositories;


use App\Models\Deposit;
use App\Models\Invoice;

class BalanceRepository
{
    public function getExportBalance($request){
        $start = $request->started." 00:00:00";
        $end = $request->ended." 23:59:59";
        $balanceData = Deposit::where("user_id",$request->userId)->wherebetween('created_at',[$start ,$end])->with("user")->get();
        return $balanceData;
    }
}
