<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\ManageAttendances;
use App\Models\Attendance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $pluralModelLabel = "Presensi";
    protected static ?string $modelLabel = "Presensi";
    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;

    protected static ?string $recordTitleAttribute = 'attendance';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attendance')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('attendance')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->state(function ($record){
                        return $record->employee_id?->name ?? $record->internship_id?->name ?? '-';
                    })
                    ->searchable(),
                TextColumn::make('time_in')
                    ->label('Waktu Masuk'),
                TextColumn::make('time_out')
                    ->label('Waktu Keluar'),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('leave_type_id.name')
                    ->label('Jenis Izin')
                    ->default('-')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAttendances::route('/'),
        ];
    }
}
