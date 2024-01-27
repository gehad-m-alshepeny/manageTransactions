<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthenticatedAPIController;
use App\Http\Controllers\Api\v1\Transaction\TransactionController;
use App\Http\Controllers\Api\v1\Payment\PaymentController;

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

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () 
{
    Route::post('login', [AuthenticatedAPIController::class, 'login']);

    Route::middleware('auth:api')->group( function () 
    {
      Route::group(["prefix" => "transactions",'middleware' => 'throttle:10,1'], function () 
      {
        Route::post('/createTransaction', [TransactionController::class, 'store']);
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/generateReport', [TransactionController::class, 'generateReport']);
      });

      Route::post('/payments/recordPayment', [PaymentController::class, 'store']);
      
    });

});
 
  
