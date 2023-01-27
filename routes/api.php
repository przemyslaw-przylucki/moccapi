<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenerateMockupController;

Route::get('/generate/{mockup}', GenerateMockupController::class);

Route::middleware('auth:sanctum')->group(function () {
});
