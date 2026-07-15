<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Auth\SetupPassword;

Route::middleware('guest')->group(function () {
    Route::get('password/setup/{token}', SetupPassword::class)
        ->name('password.setup');
});
