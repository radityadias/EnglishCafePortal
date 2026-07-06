<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\User;
use App\Observers\AttendanceObserver;
use App\Observers\UserObserver;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        User::observe(UserObserver::class);
        Attendance::observe(AttendanceObserver::class);
    }
}
