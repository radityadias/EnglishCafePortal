<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Division;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
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
                    ->label('No. Handphone')
                    ->required(),
                Select::make('division_id')
                    ->label('Divisi')
                    ->relationship('division', 'name')
                    ->placeholder('Pilih Divisi'),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    ])
                    ->placeholder('Pilih Jenis Kelamin'),
                FileUpload::make('cv_path')
                    ->label('Curriculum vitae')
                    ->disk('s3')
                    ->directory('cv')
                    ->visibility('public'),
                FileUpload::make('ktp_path')
                    ->label('KTP')
                    ->disk('s3')
                    ->directory('ktp')
                    ->visibility('public'),
                FileUpload::make('other_path')
                    ->label('Sertifikat Lainnya')
                    ->disk('s3')
                    ->multiple()
                    ->directory('other')
                    ->visibility('public'),
            ]);
    }
}
