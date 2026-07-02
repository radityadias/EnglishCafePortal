<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordSetupController;

Route::middleware('guest')->group(function () {
    Route::get('password/setup/{token}', [PasswordSetupController::class, 'showForm'])
        ->name('password.setup');

    Route::post('password/setup', [PasswordSetupController::class, 'store'])
        ->name('password.setup.store');
});

Route::get('/', function () {
    return view('welcome');
});
