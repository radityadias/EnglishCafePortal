<?php

namespace App\Filament\Resources\AttendanceRecaps;

use App\Filament\Resources\AttendanceRecaps\Pages\ManageAttendanceRecaps;
use App\Models\AttendanceRecap;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = AttendanceRecap::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Rekap Kehadiran';

    protected static ?string $recordTitleAttribute = 'attendance_recap';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_hours')
                    ->required()
                    ->numeric(),
                TextInput::make('sick_leaves')
                    ->required()
                    ->numeric(),
                TextInput::make('absent_leaves')
                    ->required()
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('attendance_recap')
            ->columns([
                TextColumn::make('total_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sick_leaves')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('absent_leaves')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->searchable(),
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
            'index' => ManageAttendanceRecaps::route('/'),
        ];
    }
}
