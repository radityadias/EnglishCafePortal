<?php

namespace App\Filament\Resources\Attendances;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\Attendances\Pages\ManageAttendances;
use App\Models\Attendance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QueueList;

    protected static string | UnitEnum | null $navigationGroup = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Presensi';

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->user->name;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('status')
                    ->default('absent'),
                DatePicker::make('checkin_date'),
                TimePicker::make('checkin_time'),
                TimePicker::make('checkout_time'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('updated_at', 'desc'))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (AttendanceStatus $state) => match ($state) {
                        AttendanceStatus::Attend => 'success',
                        AttendanceStatus::Late => 'warning',
                        AttendanceStatus::Absent => 'danger',
                        AttendanceStatus::Leave => 'info',
                    }),
                TextColumn::make('checkin_date')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                TextColumn::make('checkin_time')
                    ->label('Jam Masuk')
                    ->time()
                    ->sortable(),
                TextColumn::make('checkout_time')
                    ->label('Jam Keluar')
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
            'index' => ManageAttendances::route('/'),
        ];
    }
}
