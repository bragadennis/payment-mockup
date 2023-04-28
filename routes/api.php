<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function() {
        echo "Payments API V1";
    });


    Route::apiResource('customers', CustomerController::class);

    Route::put('customer/{payer_id}/make-transaction/pay-to', "TransactionController@makeTransaction");
});
