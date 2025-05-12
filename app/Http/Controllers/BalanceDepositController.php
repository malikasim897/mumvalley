<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
use App\Models\Deposit;
use DB;
class BalanceDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:balance.view', ['only' => ['index']]);
        $this->middleware('permission:balance.create', ['only' => ['store']]);
    }

    function index() { 
        return view('balances.deposits.index',['users'=>User::all()]);
    } 

    function store(Request $request) { 
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required',
        ]);
    try {
        return  DB::transaction(function () use($request){
               $user = User::find($request->user_id);
                $balance = Balance::create([
                    'user_id'       =>  $request->user_id,
                    'description'   =>  $request->description,
                ]);
                $deposit = Deposit::create([
                    'user_id'           => $user->id,
                    'uuid'              => uniqid(),
                    'balance'           => $request->is_credit == "credit" ? $user->current_balance + $request->amount : $user->current_balance - $request->amount ,
                    'amount'            => $request->amount,
                    'is_credit'         => $request->is_credit == "credit" ? true: false,
                    'last_four_digits'  => 'Admin add balance', 
                    'depositable_type'  => get_class($balance),
                    'depositable_id'    => $balance->id,
                ]); 
             return redirect()->route('balances.index')->with('success', 'Balance added successfully.');
        });
    }catch (\Exception $e) {
        $errorMessage = $e->getMessage();
        return back()->with('error', 'An error occurred something went wrong: ' . $errorMessage);
    }
}
}
