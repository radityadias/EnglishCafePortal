<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('position')
                            ->label('Posisi'),
                        TextEntry::make('phone')
                            ->label('No. HP'),
                    ]),

                Group::make()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 3,
                    ])
                    ->relationship('employeeProfile')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Alamat'),
                        TextEntry::make('birth_place')
                            ->label('Tempat, Tanggal Lahir')
                            ->formatStateUsing(function ($state, $record) {
                                return $record->birth_place . ', ' . $record->birth_date;
                            }),
                        TextEntry::make('bank_number')
                            ->label('No. Rekening')
                            ->formatStateUsing(function ($state, $record) {
                                return $record->bank_name . ' - ' . $record->bank_number;
                            }),
                        TextEntry::make('bank_account_name')
                            ->label('Nama Rekening'),
                        TextEntry::make('cv_path')
                            ->label('CV')
                            ->url(function ($record) {
                                if (!$record->cv_path) {
                                    return null;
                                }

                                return Storage::disk('s3')->temporaryUrl($record->cv_path, now()->addMinutes(5));
                            })
                            ->openUrlInNewTab()
                            ->color(fn($state) => $state ? 'primary' : 'gray'),
                        TextEntry::make('ktp_path')
                            ->label('KTP')
                            ->url(function ($record) {
                                if (!$record->ktp_path) {
                                    return null;
                                }

                                return Storage::disk('s3')->temporaryUrl($record->ktp_path, now()->addMinutes(5));
                            })
                            ->openUrlInNewTab()
                            ->color(fn($state) => $state ? 'primary' : 'gray')
                    ])
            ])
            ->columns(1);
    }
}
