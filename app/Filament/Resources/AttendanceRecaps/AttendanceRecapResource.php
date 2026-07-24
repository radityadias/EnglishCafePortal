<?php

namespace App\Filament\Resources\AttendanceRecaps;

use App\Filament\Resources\AttendanceRecaps\Pages\ManageAttendanceRecaps;
use App\Models\AttendanceRecap;
use App\Models\Branch;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\QueryBuilder\Constraints\NumberConstraint;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = AttendanceRecap::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleGroup;

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
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_hours')
                    ->label('Total Jam')
                    ->default('0')
                    ->sortable(),
                TextColumn::make('sick_leaves')
                    ->label('Izin Sakit')
                    ->default('0')
                    ->sortable(),
                TextColumn::make('absent_leaves')
                    ->label('Tanpa Keterangan')
                    ->default('0')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Cabang')
                    ->options(Branch::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable(),
                QueryBuilder::make()
                    ->constraints([
                        NumberConstraint::make('total_hours')
                            ->label('Total Jam')
                            ->integer(),

                        NumberConstraint::make('sick_leaves')
                            ->label('Izin Sakit')
                            ->integer(),

                        NumberConstraint::make('absent_leaves')
                            ->label('Tanpa Keterangan')
                            ->integer(),
                    ])
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
