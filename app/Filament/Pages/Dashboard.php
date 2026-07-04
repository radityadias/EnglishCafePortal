<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Dashboard extends BaseDashboard
{
    protected static ?string $model = Attendance::class;

    public static function table(Table $table): Table
    {

    }
}
