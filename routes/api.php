<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\WebhookController;
use App\Http\Controllers\API\AuthenticationController;

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
//Auth
Route::post('register' , [AuthenticationController::class , 'register']);
Route::post('login' , [AuthenticationController::class , 'login']);
//Orders
Route::resource('orders' , OrderController::class)->only(['index'  , 'store'])->middleware('auth:api');
Route::put('/orders/{order}/status' , [OrderController::class , 'updateStatus'])->middleware('auth:api');
//Payments
Route::post('/payments/purchase/{order}', [PaymentController::class, 'purchase'])->middleware('auth:api');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
//WebHook Listener
Route::post('webhook/paypal', [WebhookController::class, 'handleWebhook']);


