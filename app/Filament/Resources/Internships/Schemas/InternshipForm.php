<?php

namespace App\Filament\Resources\Internships\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class InternshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap'),
                        TextInput::make('email')
                            ->label('Email'),
                    ]),

                Group::make()
                    ->columns(2)
                    ->relationship('internshipProfile')
                    ->schema([
                        TextInput::make('nickname')
                            ->label('Nama Panggilan'),
                        TextInput::make('phone')
                            ->label('No. Telp'),
                        TextInput::make('phone_backup')
                            ->label('No. Telp Backup'),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir'),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir'),
                        TextInput::make('address')
                            ->label('Alamat'),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai'),
                        Select::make('division_id')
                            ->relationship('division', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Divisi'),
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Cabang'),
                        Select::make('instance_id')
                            ->relationship('instance', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Instansi'),
                        FileUpload::make('cv_path')
                            ->label('CV')
                            ->disk('s3')
                            ->directory('cv')
                            ->preventFilePathTampering(
                                allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'cv/')
                            ),
                    ]),
            ])
            ->columns(1);
    }
}
