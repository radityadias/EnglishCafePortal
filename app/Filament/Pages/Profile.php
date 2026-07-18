<?php

namespace App\Filament\Pages;

use App\Enums\Position;
use App\Models\Branch;
use App\Models\Division;
use App\Models\Instance;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use BackedEnum;
use UnitEnum;

class Profile extends Page
{
    public User $user;

    protected string $view = 'filament.pages.profile';
    protected static ?string $title = 'Profil Saya';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;
    protected static string|UnitEnum|null $navigationGroup = 'Akun';

    public function mount(): void
    {
        $this->user = Auth::user()->fresh()->load(
            $this->isEmployee() ? [
                'employeeProfile',
                'employeeProfile.branch',
                'employeeProfile.division',
            ] : [
                'internshipProfile',
                'internshipProfile.branch',
                'internshipProfile.division',
                'internshipProfile.instance',
            ]
        );
    }

    #[On('profile_updated')]
    public function refresh(): void
    {
        //
    }

    private function refreshPage(): void
    {
        $this->dispatch('profile_updated');
    }

    public function getHeaderActions(): array
    {
        return [
            $this->editAction()
        ];
    }

    private function isEmployee(): bool
    {
        return Auth::user()->position === Position::Employee;
    }

    public function infolist(Schema $schema): Schema
    {
        $profile = $this->isEmployee()
            ? $this->user->employeeProfile
            : $this->user->internshipProfile;

        return $schema
            ->record($this->user)
            ->components([
                Section::make('Informasi Akun')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->default('-'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->default('-'),
                        TextEntry::make('phone')
                            ->label('Nomor Telepon')
                            ->default('-'),
                    ]),

                Section::make('Informasi Pribadi')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('employeeProfile.nickname')
                            ->label('Nama Panggilan')
                            ->default('-')
                            ->visible($this->isEmployee()),
                        TextEntry::make('employeeProfile.phone_backup')
                            ->label('Nomor Telepon Cadangan'),
                        TextEntry::make('internshipProfile.nickname')
                            ->label('Nama Panggilan')
                            ->default('-')
                            ->visible(!$this->isEmployee()),
                        TextEntry::make($this->isEmployee() ? 'employeeProfile.birth_place' : 'internshipProfile.birth_place')
                            ->label('Tempat Lahir')
                            ->placeholder('-'),
                        TextEntry::make($this->isEmployee() ? 'employeeProfile.birth_date' : 'internshipProfile.birth_date')
                            ->label('Tanggal Lahir')
                            ->date('d F Y')
                            ->placeholder('-'),
                        TextEntry::make($this->isEmployee() ? 'employeeProfile.address' : 'internshipProfile.address')
                            ->label('Alamat')
                            ->default('-'),
                    ]),

                // Employee-only section
                Section::make('Informasi Kerja')
                    ->columns(3)
                    ->visible($this->isEmployee())
                    ->schema([
                        TextEntry::make('employeeProfile.bank_name')
                            ->label('Nama Bank')
                            ->default('-'),
                        TextEntry::make('employeeProfile.bank_number')
                            ->label('No. Rekening')
                            ->default('-'),
                        TextEntry::make('employeeProfile.bank_account_name')
                            ->label('Nama Pemilik Rekening')
                            ->default('-'),
                        TextEntry::make('position')
                            ->label('Posisi')
                            ->default('-'),
                        TextEntry::make('employeeProfile.branch.name')
                            ->label('Cabang')
                            ->default('-'),
                        TextEntry::make('employeeProfile.work_time_start')
                            ->label('Waktu Mulai Kerja')
                            ->default('-'),
                        TextEntry::make('employeeProfile.work_time_end')
                            ->label('Waktu Selesai Kerja')
                            ->default('-'),
                        TextEntry::make('attendanceRecap.total_hours')
                            ->label('Total Jam Kerja')
                            ->default('-'),
                    ]),

                // Internship-only section
                Section::make('Informasi Magang')
                    ->columns(3)
                    ->visible(!$this->isEmployee())
                    ->schema([
                        TextEntry::make('employeeProfile.school')
                            ->label('Asal Sekolah'),
                        TextEntry::make('internshipProfile.start_date')
                            ->label('Tanggal Mulai')
                            ->date('d MMMM Y')
                            ->placeholder('-'),
                        TextEntry::make('internshipProfile.end_date')
                            ->label('Tanggal Selesai')
                            ->date('d MMMM Y')
                            ->placeholder('-'),
                        TextEntry::make('attendanceRecap.total_hours')
                            ->label('Total Jam Kerja')
                            ->default('-'),
                        TextEntry::make('internshipProfile.branch.name')
                            ->label('Branch')
                            ->default('-'),
                        TextEntry::make('internshipProfile.division.name')
                            ->label('Divisi')
                            ->default('-'),
                        TextEntry::make('internshipProfile.instance.name')
                            ->label('Instansi')
                            ->default('-'),
                    ]),

                Section::make('Dokumen')
                    ->columns(3)
                    ->schema([
                        TextEntry::make($this->isEmployee() ? 'employeeProfile.cv_path' : 'internshipProfile.cv_path')
                            ->label('CV')
                            ->formatStateUsing(fn ($state) => $state ? basename($state) : '-')
                            ->url(fn($state) => $state
                                ? Storage::disk('s3')->temporaryUrl($state, now()->addMinutes(5))
                                : null)
                            ->openUrlInNewTab()
                            ->default('-')
                            ->color(fn($state) => $state ? 'info' : 'gray'),
                        TextEntry::make('employeeProfile.ktp_path')
                            ->label('KTP')
                            ->color(fn($state) => $state ? 'info' : 'gray')
                            ->visible($this->isEmployee())
                            ->formatStateUsing(fn ($state) => $state ? basename($state) : '-')
                            ->url(fn($state) => $state
                                ? Storage::disk('s3')->temporaryUrl($state, now()->addMinutes(5))
                                : null)
                            ->openUrlInNewTab()
                            ->default('-'),

                    ]),
            ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Edit Profil')
            ->icon(Heroicon::PencilSquare)
            ->modalHeading('Edit Profil')
            ->modalDescription('Perbarui informasi profil Anda.')
            ->modalWidth('2xl')
            ->fillForm(function (): array {
                $user = $this->user;
                $profile = $this->isEmployee()
                    ? $user->employeeProfile
                    : $user->internshipProfile;

                $base = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'nickname' => $profile?->nickname,
                    'birth_place' => $profile?->birth_place,
                    'birth_date' => $profile?->birth_date,
                    'address' => $profile?->address,
                    'cv_path' => $profile?->cv_path,
                    'branch_id' => $profile?->branch_id,
                    'division_id' => $profile?->division_id,
                ];

                if ($this->isEmployee()) {
                    return array_merge($base, [
                        'phone_backup' => $profile?->phone_backup,
                        'bank_name' => $profile?->bank_name,
                        'bank_number' => $profile?->bank_number,
                        'bank_account_name' => $profile?->bank_account_name,
                        'ktp_path' => $profile?->ktp_path,
                        'work_time_start' => $profile?->work_time_start,
                        'work_time_end' => $profile?->work_time_end,
                    ]);
                }

                return array_merge($base, [
                    'start_date' => $profile?->start_date,
                    'end_date' => $profile?->end_date,
                ]);
            })
            ->schema($this->getEditFormSchema())
            ->action(function (array $data): void {
                $user = Auth::user();

                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]);

                if (!empty($data['new_password'])) {
                    $user->update([
                        'password' => Hash::make($data['new_password']),
                    ]);
                }

                $profileData = [
                    'nickname' => $data['nickname'],
                    'birth_place' => $data['birth_place'],
                    'birth_date' => $data['birth_date'],
                    'address' => $data['address'],
                    'cv_path' => $data['cv_path'] ?? null,
                    'branch_id' => $data['branch_id'] ?? null,
                    'division_id' => $data['division_id'] ?? null,
                ];

                if ($this->isEmployee()) {
                    $user->employeeProfile()->updateOrCreate(
                        ['user_id' => $user->id],
                        array_merge($profileData, [
                            'phone_backup' => $data['phone_backup'] ?? null,
                            'bank_name' => $data['bank_name'] ?? null,
                            'bank_number' => $data['bank_number'] ?? null,
                            'bank_account_name' => $data['bank_account_name'] ?? null,
                            'ktp_path' => $data['ktp_path'] ?? null,
                            'work_time_start' => $data['work_time_start'] ?? null,
                            'work_time_end' => $data['work_time_end'] ?? null,
                        ])
                    );
                } else {
                    $user->internshipProfile()->updateOrCreate(
                        ['user_id' => $user->id],
                        array_merge($profileData, [
                            'start_date' => $data['start_date'] ?? null,
                            'end_date' => $data['end_date'] ?? null,
                            'instance_id' => $data['instance_id'] ?? null,
                        ])
                    );
                }

                $this->refreshPage();

                Notification::make()
                    ->title('Profil berhasil diperbarui.')
                    ->success()
                    ->send();
            });
    }

    private function getEditFormSchema(): array
    {
        $shared = [
            Section::make('Informasi Akun')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    TextInput::make('phone')
                        ->label('Nomor Telepon')
                ]),

            Section::make('Informasi Pribadi')
                ->columns(2)
                ->schema([
                    TextInput::make('nickname')
                        ->label('Nama Panggilan'),
                    TextInput::make('phone_backup')
                        ->label('Nomor Telepon Cadangan')
                        ->visible($this->isEmployee()),
                    TextInput::make('birth_place')
                        ->label('Tempat Lahir'),
                    DatePicker::make('birth_date')
                        ->label('Tanggal Lahir'),
                    TextInput::make('address')
                        ->label('Alamat'),
                    TextInput::make('school')
                        ->label('Asal Sekolah')
                        ->visible(!$this->isEmployee()),
                ]),

            Section::make('Dokumen')
                ->columns(fn(): int => $this->isEmployee() ? 2 : 1)
                ->schema([
                    FileUpload::make('cv_path')
                        ->label('CV')
                        ->disk('s3')
                        ->directory('cv')
                        ->preventFilePathTampering(
                            allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'cv/')
                        ),
                    FileUpload::make('ktp_path')
                        ->label('KTP')
                        ->visible($this->isEmployee())
                        ->disk('s3')
                        ->directory('ktp')
                        ->preventFilePathTampering(
                            allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'ktp/')
                        ),
                ]),

            Section::make('Keamanan')
                ->columns(2)
                ->schema([
                    TextInput::make('current_password')
                        ->label('Password Saat Ini')
                        ->password()
                        ->revealable()
                        ->requiredWith('new_password')
                        ->currentPassword(),
                    TextInput::make('new_password')
                        ->label('Password Baru')
                        ->password()
                        ->revealable()
                        ->requiredWith('current_password')
                        ->confirmed(),
                    TextInput::make('new_password_confirmation')
                        ->label('Konfirmasi Password Baru')
                        ->password()
                        ->revealable()
                        ->requiredWith('new_password'),
                ])
        ];

        if ($this->isEmployee()) {
            $shared[] =
                Section::make('Informasi Kerja')
                    ->columns(2)
                    ->schema([
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
                                'Jago' => 'Jago',
                            ])
                            ->searchable(),
                        TextInput::make('bank_number')
                            ->label('No. Rekening'),
                        TextInput::make('bank_account_name')
                            ->label('Nama Pemilik Rekening'),
                        TimePicker::make('work_time_start')
                            ->label('Waktu Mulai Kerja'),
                        TimePicker::make('work_time_end')
                            ->label('Waktu Selesai Kerja'),
                        Select::make('branch_id')
                            ->label('Branch')
                            ->options(Branch::all()->pluck('name', 'id'))
                            ->preload()
                            ->searchable(),
                        Select::make('division_id')
                            ->label('Divisi')
                            ->options(Division::all()->pluck('name', 'id'))
                            ->preload()
                            ->searchable(),
                    ]);
        } else {
            $shared[] = Section::make('Informasi Magang')
                ->columns(2)
                ->schema([
                    Select::make('branch_id')
                        ->label('Branch')
                        ->options(Branch::all()->pluck('name', 'id'))
                        ->preload()
                        ->searchable(),
                    Select::make('division_id')
                        ->label('Divisi')
                        ->options(Division::all()->pluck('name', 'id'))
                        ->preload()
                        ->searchable(),
                    Select::make('instance_id')
                        ->label('Instansi')
                        ->options(Instance::all()->pluck('name', 'id'))
                        ->preload()
                        ->searchable(),
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai'),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->afterOrEqual('start_date'),
                ]);
        }

        return $shared;
    }
}
