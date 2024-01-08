<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplyStockInRequestController;
use App\Http\Controllers\SupplyStockInRequestDetailController;
use App\Http\Controllers\SalesOrderRequestController;
use App\Http\Controllers\SalesOrderRequestDetailController;
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


    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::post('user/{user}/change_password', [UserController::class, 'change']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('/supplies', [SupplyController::class, 'index']);
    Route::post('/supply/store', [SupplyController::class, 'store']);
    Route::post('/supply/store/exist/{supply}', [SupplyController::class, 'store_exist']);
    Route::post('/supply/update/{supply}', [SupplyController::class, 'update']);
    Route::post('/supply/delete/{supply}', [SupplyController::class, 'destroy']);

    Route::get('/stock_in_requests', [SupplyStockInRequestController::class, 'index']);
    Route::get('/stock_in_requests/pending', [SupplyStockInRequestController::class, 'pending']);
    Route::post('/stock_in_request/store', [SupplyStockInRequestController::class, 'store']);
    Route::post('/stock_in_request/update/{supplyStockInRequest}', [SupplyStockInRequestController::class, 'update']);
    Route::post('/stock_in_request/delete/{supplyStockInRequest}', [SupplyStockInRequestController::class, 'destroy']);

    Route::get('/sales_order_requests', [SalesOrderRequestController::class, 'index']);
    Route::get('/sales_order_requests/pending', [SalesOrderRequestController::class, 'pending']);
    Route::post('/sales_order_request/store', [SalesOrderRequestController::class, 'store']);
    Route::post('/sales_order_request/update/{salesOrderRequest}', [SalesOrderRequestController::class, 'update']);
    Route::post('/sales_order_request/delete/{salesOrderRequest}', [SalesOrderRequestController::class, 'destroy']);
    
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/supplier/store', [SupplierController::class, 'store']);
    Route::post('/supplier/update/{supplier}', [SupplierController::class, 'update']);
    Route::post('/supplier/delete/{supplier}', [SupplierController::class, 'destroy']);
    
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::post('customer/{customer}', [CustomerController::class, 'update']);
    Route::post('customer/delete/{id}', [CustomerController::class, 'destroy']);

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


