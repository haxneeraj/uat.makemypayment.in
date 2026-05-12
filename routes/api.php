<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayoutController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\CallbackController;

Route::group(['prefix' => 'v1'], function () {
    Route::middleware('api.security')->group(function () {
        Route::get('/balance', [PayoutController::class, 'getBalance']);

        Route::prefix('payouts')->group(function () {
            Route::post('/initiate', [PayoutController::class, 'initiate']);
            Route::post('/bulk', [PayoutController::class, 'bulk']);
            Route::get('/status', [PayoutController::class, 'checkStatus']);
        });
    });

    // webhook
    Route::post('/webhook', [WebhookController::class, 'handle']);
    Route::post('/callback/springnxt-2fa', [CallbackController::class, 'springNxt2FA']);
    Route::post('/callback/sprintnxt-payout', [CallbackController::class, 'sprintnxtCallback']);
});