<?php

use App\Http\Controllers\Api\PublicApi\APIStateController;
use App\Http\Controllers\Api\PublicApi\APICountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\PublicApi\ServicesController;
use App\Http\Controllers\Api\PublicApi\APIOrderController;
use App\Http\Controllers\Api\PublicApi\APIShCodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/print-label', [LoginController::class, 'printLabel']);
Route::post('/create-order', [LoginController::class, 'createOrder']);

Route::group(["prefix" => "v1", 'middleware' => 'checkAuth'], function () {
    Route::get('hd-shipping-services', [ServicesController::class, 'hd_services']);
    Route::get('shipping-services', [ServicesController::class, 'user_services']);
    Route::post('orders', [APIOrderController::class, 'store']);
    Route::get('countries', [APICountryController::class, 'index']);
    Route::get('country/{id}/states', [APIStateController::class, 'index']);
    Route::get('shcodes', [APIShCodeController::class, 'index']);
    Route::get('order/{order}/label', [APIOrderController::class, 'label']);
});
