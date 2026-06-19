<?php

namespace App\Filament\Resources\Internships;

use App\Filament\Resources\Internships\Pages\ManageInternships;
use App\Models\Internship;
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

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;
    protected static ?string $pluralModelLabel = "Internship";
    protected static ?string $modelLabel = "Internship";
    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;
    protected static string | UnitEnum | null $navigationGroup = 'Biodata';
    protected static ?string $recordTitleAttribute = 'internship';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('internship')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('internship')
            ->columns([
                TextColumn::make('internship')
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
            'index' => ManageInternships::route('/'),
        ];
    }
}
