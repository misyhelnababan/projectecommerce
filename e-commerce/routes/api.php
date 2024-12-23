<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\RajaOngkirController;
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
Route::resource('categories',CategoryController::class);
Route::resource('products',ProductController::class);
Route::resource('pelanggan',PelangganController::class);
Route::resource('transaksi',TransaksiController::class);
Route::get('provinces', [RajaOngkirController::class, 'provinces']);
Route::get('cities/{provinceId}', [RajaOngkirController::class, 'cities']);
Route::post('cost', [RajaOngkirController::class, 'cekOngkir']);
