<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['message' => 'DB Connection is successful!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to connect to DB: ' . $e->getMessage()], 500);
    }
});