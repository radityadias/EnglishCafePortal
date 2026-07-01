<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Branch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use App\Models\User;

class EmployeeForm
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
                        TextInput::make('phone')
                            ->label('No. Telp'),
                    ]),

                Group::make()
                    ->columns(2)
                    ->relationship('employeeProfile')
                    ->schema([
                        TextInput::make('nickname')
                            ->label('Nama Panggilan'),
                        TextInput::make('phone_backup')
                            ->label('No. Telp Backup'),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir'),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir'),
                        TextInput::make('address')
                            ->label('Alamat'),
                        TimePicker::make('work_time_start')
                            ->label('Waktu Mulai Kerja'),
                        TimePicker::make('work_time_end')
                            ->label('Waktu Selesai Kerja'),
                        Select::make('bank_name')
                            ->label('Nama Bank')
                            ->options([
                                'BCA' => 'BCA',
                                'BNI' => 'BNI',
                                'BRI' => 'BRI',
                                'Mandiri' => 'Mandiri',
                                'Seabank' => 'Seabank',
                                'BPD' => 'BPD',
                                'BSI' => 'BSI',
                                'Jago' => 'Jago'
                            ])
                            ->searchable(),
                        TextInput::make('bank_number')
                            ->label('No. Rekening'),
                        TextInput::make('bank_account_name')
                            ->label('Nama Pemilik Rekening'),
                        FileUpload::make('cv_path')
                            ->label('CV')
                            ->disk('s3')
                            ->directory('cv')
                            ->preventFilePathTampering(
                                allowFilePathUsing: fn (string $file): bool => str_starts_with($file, 'cv/')
                            ),
                        FileUpload::make('ktp_path')
                            ->label('KTP')
                            ->disk('s3')
                            ->directory('ktp')
                            ->preventFilePathTampering(
                                allowFilePathUsing: fn (string $file): bool => str_starts_with($file, 'ktp/')
                            )
                    ]),
            ])
            ->columns(1);
    }
}
