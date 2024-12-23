<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParsingDataController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\PelangganController;
use App\Http\Controllers\Dashboard\TransaksiController;
use App\Http\Controllers\Dashboard\CekOngkirController;
use App\Http\Controllers\Dashboard\CheckoutController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard.index');
})->name('dashboard');

Auth::routes();

Route::prefix('dashboard')->middleware(['auth','profile.check'])->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index', ['title' => 'Dashboard']);
    })->name('index');
    

    Route::get('/users', [UserController::class, 'index']);           // Baca semua data
    Route::post('/users', [UserController::class, 'store']);           // Tambah data
    Route::put('/users/{id}', [UserController::class, 'update']);      // Perbarui data
    Route::delete('/users/{id}', [UserController::class, 'destroy']);  // Hapus data

    Route::resource('products', ProductController::class)->only([
        'index', 'store', 'destroy', 'edit', 'update'
    ])->names('products');
    Route::resource('cek-ongkir', CekOngkirController::class)->names('cek-ongkir');
    Route::resource('checkout', CheckoutController::class)->names('checkout');
    Route::resource('products', ProductController::class)->names('products');


    Route::redirect('test', 'products');
});

Route::resource('dashboard/profile',ProfileController::class)->only([
    'index','update'
])->names('dashboard.profile');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route group dengan prefix 'admin'
Route::prefix('admin')->group(function () {
    Route::get('/kontakkami', function () {
        return view('dashboard.kontakkami');
    })->name('kontakkami');
});

Route::get('/parse-data/{nama_lengkap}/{email}/{jenis_kelamin}', [ParsingDataController::class, 'parseData'])
    ->name('parse.data');

Route::get('/kategori', function () {
    return view('kategori');
})->name('kategori');

Route::get('/transaksi', function () {
    return view('transaksi');
})->name('transaksi');

Route::resource('pelanggan', PelangganController::class);
Route::resource('categories', CategoryController::class);
Route::resource('transaksi', TransaksiController::class);

// Rute login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.request');
    Route::get('/reset-password/{email}', [ResetPasswordController::class, 'showResetForm'])->name('reset.password.form');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset.password'); // Rute untuk memproses reset password

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset.password'); // Rute untuk memproses reset password

    


