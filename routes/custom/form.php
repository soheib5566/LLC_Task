<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::middleware('auth')->group(function () {
    Route::get('/application', [ApplicationController::class, 'create'])->name('application.create');
});
