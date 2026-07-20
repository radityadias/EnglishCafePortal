<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
            ->query(fn (): Builder => \App\Models\Attendance::query()
                ->where('attendances.user_id', Auth::id())
                ->orderBy('checkin_date', 'desc')
                ->orderBy('updated_at', 'desc'))
            ->heading('Presensi')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama'),
                TextColumn::make('checkin_date')
                    ->label('Tanggal')
                    ->date('d F Y'),
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
                        AttendanceStatus::Leave => 'info',
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }


}
