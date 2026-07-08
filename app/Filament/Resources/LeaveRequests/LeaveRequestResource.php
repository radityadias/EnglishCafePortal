<?php

namespace App\Filament\Resources\LeaveRequests;

use App\Filament\Resources\LeaveRequests\Pages\ManageLeaveRequests;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Enums\ConfirmationStatus;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Daftar Permintaan Cuti';

    protected static ?string $recordTitleAttribute = 'leave_request';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                ->label ('Tanggal Mulai'),
                DatePicker::make('end_date')
                ->label ('Tanggal Selesai'),
                Select::make('status')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                FileUpload::make('image')
                    ->image(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->preload()
                    ->label ('Nama Pengguna')
                    ->searchable()
                    ->required(),
                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->label ('Jenis Cuti')
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('leave_request')
            ->columns([
                TextColumn::make('user.name')
                ->searchable()
                ->label ('Nama'),
                TextColumn::make('leaveType.name')
                    ->searchable()
                    ->label ('Jenis'),
                TextColumn::make('start_date')
                ->date()
                    ->sortable()
                    ->label ('Tanggal Mulai'),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label ('Tanggal Selesai'),
                TextColumn::make('status')
                    ->searchable(),
                TextCoulmn::make('status')
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
            'index' => ManageLeaveRequests::route('/'),
        ];
    }
}
