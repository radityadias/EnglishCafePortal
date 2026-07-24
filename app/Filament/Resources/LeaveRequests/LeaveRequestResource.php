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
use App\Enums\LeaveType;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Enums\ConfirmationStatus;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PaperAirplane;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Permintaan Libur';

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->user->name;
    }
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
                FileUpload::make('image_path')
                    ->label('Bukti Foto')
                    ->icon(Heroicon::Photo)
                    ->formatStateUsing(fn ($state) => $state ? 'Foto' : '-')
                    ->url(function ($record) {
                        if (!$record->image_path) {
                            return null;
                        }
                        return Storage::disk('s3')->temporaryUrl($record->image_path, now()->addMinutes(5));
                    })
                    ->openUrlInNewTab()
                    ->color(fn ($state) => $state ? 'blue' : 'gray')
                    ->searchable(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->preload()
                    ->label ('Nama Pengguna')
                    ->searchable()
                    ->required(),
                Select::make('type')
                    ->label ('Jenis Cuti')
                    ->options([
                        LeaveType::Sick->value => 'Sakit',
                        LeaveType::Leave->value => 'Cuti',
                        LeaveType::Personal->value => 'Keperluan Pribadi',
                        LeaveType::Other->value => 'Lainnya',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label ('Nama')
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable()
                    ->label ('Jenis')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label ('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label ('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        ConfirmationStatus::Pending->value => 'Pending',
                        ConfirmationStatus::Approved->value => 'Approved',
                        ConfirmationStatus::Rejected->value => 'Rejected',
                    ])
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ConfirmationStatus::Pending->value => 'Pending',
                        ConfirmationStatus::Approved->value => 'Approved',
                        ConfirmationStatus::Rejected->value => 'Rejected',
                    ])
                    ->multiple()
                    ->searchable(),
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
