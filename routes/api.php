<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenerateMockupController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/generate/{mockup}', GenerateMockupController::class)->middleware('throttle:10,1');
});
