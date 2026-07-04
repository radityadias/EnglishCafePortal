<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AttendanceTable extends TableWidget
{
    public static int | null $sort = 3;
    public array | int | string $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => \App\Models\Attendance::query())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama'),
                TextColumn::make('type')
                    ->label('Jenis'),
                TextColumn::make('time')
                    ->label('Waktu')
                    ->formatStateUsing(function ($record): string {
                        return $record->checkout_time ? $record->checkout_time : $record->checkin_time;
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (AttendanceStatus $state) => match ($state) {
                        AttendanceStatus::Attend => 'success',
                        AttendanceStatus::Late => 'warning',
                        AttendanceStatus::Absent => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
