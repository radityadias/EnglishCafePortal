<?php

namespace App\Filament\Resources\AttendanceRequests;

use App\Filament\Resources\AttendanceRequests\Pages\ManageAttendanceRequests;
use App\Models\AttendanceRequest;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Enums\ConfirmationStatus;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class AttendanceRequestResource extends Resource
{
    protected static ?string $model = AttendanceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

     protected static ?string $pluralModelLabel = 'Permintaan Absen';

    protected static ?string $recordTitleAttribute = 'attendance_request';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->label('Jenis'),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->maxDate(Carbon::now()),
                TimePicker::make('requested_time')
                    ->label('Request Waktu'),
                Textarea::make('reason')
                    ->label('Alasan'),
                FileUpload::make('image_path')
                    ->label('Bukti Foto')
                    ->image()
                    ->directory('absen')
                    ->disk('s3')
                    ->preventFilePathTampering(
                        allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'absen/')
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('attendance_request')
            ->query(fn (): Builder => AttendanceRequest::query()
                ->where('status', ConfirmationStatus::Pending))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('requested_time')
                    ->label('Request Waktu')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->searchable()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        ConfirmationStatus::Approved->value => 'Setuju',
                        ConfirmationStatus::Rejected->value => 'Tolak',
                        ConfirmationStatus::Pending->value => 'Pending',
                    ]),
                TextColumn::make('image_path')
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
