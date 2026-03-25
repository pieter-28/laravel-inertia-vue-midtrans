<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DahsboardController;

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
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DahsboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    #checkout
    Route::get('checkout/{id}', [ProductController::class, 'checkout'])->name('checkout');

    #payment
    Route::post('/payment', [PaymentController::class, 'payment'])->name('payment');

    #resource
    Route::resource('product', ProductController::class);
    Route::resource('transaction', TransactionController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
