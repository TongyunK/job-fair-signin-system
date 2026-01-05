<?php

use App\Http\Controllers\Api\QueueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('queue')->group(function () {
    Route::post('/get-number', [QueueController::class, 'getNumber']);
    Route::get('/status/{number}', [QueueController::class, 'checkStatus']);
    Route::get('/current', [QueueController::class, 'getCurrentQueue']);
});

