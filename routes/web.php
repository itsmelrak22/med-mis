<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function() {

    Route::get('/generate-order-slip-{id}', [PDFController::class, 'generateOrderSlip']);
    Route::get('/generate-stock-in-request', [PDFController::class, 'generateStockInRequestAll']);
    Route::get('/generate-stock-in-request-approved', [PDFController::class, 'generateStockInApproved']);
    Route::get('/generate-stock-in-request-pending', [PDFController::class, 'generateStockInPending']);
    Route::get('/generate-stock-in-request-cancelled', [PDFController::class, 'generateStockInCancelled']);
    Route::get('/generate-stock-in-request-return-to-seller', [PDFController::class, 'generateStockInReturnToSeller']);
    /*  
    |
    | Some Routes needed to be before the route with "{any?}" parameter.
    | So that it will be found first, after entering the route.
    | if route is found before the "{any?}" it will be initiate and the interpreter wont continue to next line of code.
    */

    Route::get('/auth-user', [AuthenticatedSessionController::class, 'show']);
    
    Route::get('/{any?}', [SpaController::class, 'index'])->where('any', '.*');
});
