<?php

namespace App\Filament\Resources\AttendanceRequests;

use App\Filament\Resources\AttendanceRequests\Pages\ManageAttendanceRequests;
use App\Models\AttendanceRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Enums\ConfirmationStatus;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceRequestResource extends Resource
{
    protected static ?string $model = AttendanceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

     protected static ?string $pluralModelLabel = 'Ijin Manual';

    protected static ?string $recordTitleAttribute = 'attendance_request';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attendance_request')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('attendance_request')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->searchable(),
                TextColumn::make('image_path')
                    ->label('Bukti Foto')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ConfirmationStatus $state) => match ($state) {
                        ConfirmationStatus::Pending => 'warning',
                        ConfirmationStatus::Approved => 'success',
                        ConfirmationStatus::Rejected => 'danger',
                    }),
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
            'index' => ManageAttendanceRequests::route('/'),
        ];
    }
}
