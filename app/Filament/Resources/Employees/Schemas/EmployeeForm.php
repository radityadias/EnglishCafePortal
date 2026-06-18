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
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->label('Phone number'),
                Select::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name'),
                Select::make('employment_status')
                    ->label('Employment status')
                    ->options([
                        'staff' => 'Staff',
                        'part_time' => 'Part time',
                        'internship' => 'Internship',
                    ]),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                TextInput::make('bank_number')
                    ->label('Bank number'),
                FileUpload::make('cv_path')
                    ->label('Curriculum vitae')
                    ->disk('s3')
                    ->directory('cv')
                    ->visibility('public'),
            ]);
    }
}
