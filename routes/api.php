<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationResponseController;

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

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);

Route::post('/user', [UserController::class, 'getUserByEmail']);

Route::post('/business', [BusinessController::class, 'store']);

Route::get('/vendor/{vendor_id}/businesses', [BusinessController::class, 'getVendorBusinesses']);

Route::get('/business/{business}', [BusinessController::class, 'show']);

Route::put('/business/{business}', [BusinessController::class, 'update']);

Route::get('/business/search/{location}', [BusinessController::class, 'searchByLocation']);

Route::post('/quotations', [QuotationController::class, 'store']);

Route::get('/quotations/check', [QuotationController::class, 'checkUserQuotation']);

Route::get('/quotations/vendor/{vendor_id}', [QuotationController::class, 'getVendorQuotations']);

Route::post('/quotation-response', [QuotationResponseController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
