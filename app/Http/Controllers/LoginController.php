<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Models\Order;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    //
    function printLabel(Request $request)
    {
        try {

            $token = $request->bearerToken();

            $user = User::where('token', $token)->first();
            $orders = Order::where('user_id', $user->id)->get();

            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Authenticate the user
            if (Auth::loginUsingId($user->id)) {

                $orderData = $orders->map(function ($order) {
                    return [
                        'label_url' => stripslashes($order->label_url),
                        'tracking_code' => ($order->tracking_code),
                    ];
                });

                return response()->json(
                    [
                        'status' => true,
                        "message" => "Lable Generated successfully",
                        'orders' => $orderData,
                    ]
                );

            } else {
                return response()->json(['error' => 'Invalid token'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    function createOrder(Request $request)
    {
        try {
            $token = $request->bearerToken();

            $user = User::where('token', $token)->first();
            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Authenticate the user
            if (Auth::loginUsingId($user->id)) {
                return new OrderResource($request->all());
            } else {
                return response()->json(['error' => 'Invalid token'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
