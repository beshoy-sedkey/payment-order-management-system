<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\WebhookController;
use App\Http\Controllers\API\PermissionController;
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
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);


Route::middleware(['auth:api'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);
    Route::post('users/{user}/roles', [RoleController::class, 'assignRoles']);
    //Orders
    Route::resource('orders', OrderController::class)->only(['index', 'store']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('/payments/purchase/{order}', [PaymentController::class, 'purchase']);
});

Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
//WebHook Listener
Route::post('webhook/paypal', [WebhookController::class, 'handleWebhook']);
