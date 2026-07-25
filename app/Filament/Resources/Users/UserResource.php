<?php

namespace App\Filament\Resources\Users;

use App\Enums\Position;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\Actions\SendSetupLinkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static string | UnitEnum | null $navigationGroup = 'Keamanan';

    protected static ?string $recordTitleAttribute = 'user';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->required(),
                Select::make('position')
                    ->label('Posisi')
                    ->required()
                    ->options([
                        Position::Employee->value => 'Karyawan',
                        Position::Internship->value => 'Internship',
                        Position::Onboarding->value => 'Onboarding',
                        Position::Training->value => 'Training',
                        Position::Nonactive->value => 'Nonaktif',
                        Position::Admin->value => 'Admin',
                        Position::SuperAdmin->value => 'Super Admin',
                    ])
                    ->default('Nonaktif'),
                Select::make('roles ')
                    ->label('Role')
                    ->relationship('roles', 'name')
        	        ->multiple(1)
		            ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Posisi')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->label('Posisi')
                    ->options([
                        Position::Employee->value => 'Karyawan',
                        Position::Internship->value => 'Internship',
                        Position::Onboarding->value => 'Onboarding',
                        Position::Training->value => 'Training',
                        Position::Nonactive->value => 'Nonaktif',
                        Position::Admin->value => 'Admin',
                        Position::SuperAdmin->value => 'Super Admin',
                    ])
                    ->searchable()
            ])
            ->recordActions([
                SendSetupLinkAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus'),
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
