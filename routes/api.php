<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\OrderDetailController;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


    Route::get('/users', [UserController::class, 'index']);


    Route::get('/supplies', [SupplyController::class, 'index']);
    Route::post('/supply/store', [SupplyController::class, 'store']);
    Route::post('/supply/update/{supply}', [SupplyController::class, 'update']);
    Route::post('/supply/delete/{supply}', [SupplyController::class, 'destroy']);
    
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/supplier/store', [SupplierController::class, 'store']);
    Route::post('/supplier/update/{supplier}', [SupplierController::class, 'update']);
    Route::post('/supplier/delete/{supplier}', [SupplierController::class, 'destroy']);
    
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::put('customers/{id}', [CustomerController::class, 'update']);
    Route::delete('customers/{id}', [CustomerController::class, 'destroy']);

    // Sales Orders routes
    Route::get('sales_orders', [SalesOrderController::class, 'index']);
    Route::get('sales_orders/{id}', [SalesOrderController::class, 'show']);
    Route::post('sales_orders', [SalesOrderController::class, 'store']);
    Route::put('sales_orders/{id}', [SalesOrderController::class, 'update']);
    Route::delete('sales_orders/{id}', [SalesOrderController::class, 'destroy']);

    // Order Details routes
    Route::get('order_details', [OrderDetailController::class, 'index']);
    Route::get('order_details/{id}', [OrderDetailController::class, 'show']);
    Route::post('order_details', [OrderDetailController::class, 'store']);
    Route::put('order_details/{id}', [OrderDetailController::class, 'update']);
    Route::delete('order_details/{id}', [OrderDetailController::class, 'destroy']);
