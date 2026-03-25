<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/test', function () {
    return response()->json(['message' => 'API OK']);
});

Route::post('/webhook', [PaymentController::class, 'handleWebhook'])->name('webhook');
