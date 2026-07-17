<?php

namespace App\Filament\Resources\Announcements;

use App\Filament\Resources\Announcements\Pages\ManageAnnouncements;
use App\Models\Announcement;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';
    protected static ?string $pluralModelLabel = 'Pengumuman';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            RichEditor::make('content')
                ->label('Isi Pengumuman')
                ->required()
                ->columnSpanFull(),

            Select::make('priority')
                ->label('Prioritas')
                ->options([
                    'info'    => 'Info',
                    'warning' => 'Peringatan',
                    'danger'  => 'Penting',
                ])
                ->nullable()
                ->placeholder('Tidak Ada Prioritas'),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),

            DateTimePicker::make('starts_at')
                ->label('Mulai Tampil')
                ->nullable(),

            DateTimePicker::make('ends_at')
                ->label('Selesai Tampil')
                ->nullable()
                ->afterOrEqual('starts_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'info'    => 'info',
                        'warning' => 'warning',
                        'danger'  => 'danger',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'info'    => 'Info',
                        'warning' => 'Peringatan',
                        'danger'  => 'Penting',
                        default   => 'Tidak Ada',
                    }),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
                TextColumn::make('ends_at')
                    ->label('Selesai')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
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
            'index' => ManageAnnouncements::route('/'),
        ];
    }
}
