<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Contracts\HasForms;
use BackedEnum;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Profile extends Page implements hasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.profile';
    protected static ?string $model = User::class;
    protected static ?string $title = 'Profile';
    protected static null | string | UnitEnum $navigationGroup = 'Akun';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

//    public function mount(): void
//    {
//        $user = Auth::user()->load('employeeProfile');
//
//        dd([
//            'user' => $user->toArray(),
//            'employeeProfile' => $user->employeeProfile?->toArray(),
//        ]);
//    }

    public function form(Schema $schema): Schema
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
            ]);
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->action(function (array $data): void {
                $user = Auth::user();
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]);
                $user->employeeProfile()->update([
                    'nickname' => $data['nickname'],
                    'birth_place' => $data['birth_place'],
                    'birth_date' => $data['birth_date'],
                    'address' => $data['address'],
                    'work_time_start' => $data['work_time_start'],
                    'work_time_end' => $data['work_time_end'],
                    'bank_name' => $data['bank_name'],
                    'bank_number' => $data['bank_number'],
                    'bank_account_name' => $data['bank_account_name'],
                    'cv_path' => $data['cv_path'],
                    'ktp_path' => $data['ktp_path'],
                ]);
            });
    }
}
