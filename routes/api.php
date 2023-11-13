<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplierController;
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
    