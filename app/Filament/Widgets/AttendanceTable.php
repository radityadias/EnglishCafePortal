<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class AttendanceTable extends TableWidget
{
    public static int | null $sort = 3;
    public array | int | string $columnSpan = 'full';

    #[On('attendance_scanned')]
    public function refresh(): void
    {
        //
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('30s')
            ->query(fn (): Builder => \App\Models\Attendance::query()->orderBy('updated_at', 'asc'))
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
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
