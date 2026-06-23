<?php

namespace App\Filament\Resources\WorkingTimes;

use App\Filament\Resources\WorkingTimes\Pages\ManageWorkingTimes;
use App\Models\WorkingTime;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WorkingTimeResource extends Resource
{
    protected static ?string $model = WorkingTime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $recordTitleAttribute = 'working_time';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TimePicker::make('from')
                    ->required(),
                TimePicker::make('to')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('working_time')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('from')
                    ->time()
                    ->sortable(),
                TextColumn::make('to')
                    ->time()
                    ->sortable(),
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
            'index' => ManageWorkingTimes::route('/'),
        ];
    }
}
