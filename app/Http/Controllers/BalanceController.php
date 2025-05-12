<?php

namespace App\Http\Controllers;

use App\Repositories\BalanceRepository;
use App\Services\Excel\Export\ExportBalance;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    protected  $balanceRepository;
    public function __construct(
        BalanceRepository $balanceRepository
    )
    {
        $this->balanceRepository = $balanceRepository;
        $this->middleware('permission:balance.view', ['only' => ['index']]);
    }
    public function index(){
        return view('balances.index');
    }

    public function  exportBalance(Request $request){
        $exportUsers = new ExportBalance(
            $this->balanceRepository->getExportBalance($request)
        );
        return $exportUsers->handle();
    }

}
