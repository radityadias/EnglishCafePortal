<?php

namespace App\Filament\Resources\ClassSessions;

use App\Filament\Resources\ClassSessions\Pages\ManageClassSessions;
use App\Models\ClassSession;
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
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Laporan Kelas';

    protected static ?string $recordTitleAttribute = 'user_id.name';

    public static function canViewAny(): bool
    {
        return Auth::user()->canAccessKpiFeatures() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id.name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id.name')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type'),
                TextColumn::make('date')
                    ->label('Tanggal'),
                TextColumn::make('start_time')
                    ->label('Waktu Mulai'),
                TextColumn::make('end_time')
                    ->label('Waktu Selesai'),
                TextColumn::make('notes')
                    ->label('Catatan'),
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
            'index' => ManageClassSessions::route('/'),
        ];
    }
}
