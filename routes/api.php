<?php

use App\Http\Controllers\TrackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:60,1')->group(function () {
    Route::post('/track', [TrackController::class, 'store'])->name('track.store');
    Route::post('/wb/ingest', [TrackController::class, 'store'])->name('track.ingest'); // alternativ väg
});

Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');
