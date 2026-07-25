<?php

namespace App\Filament\Actions\Attendance;

use App\Enums\AttendanceStatus;
use App\Enums\ConfirmationStatus;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Illuminate\Support\Facades\Auth;

class AttendanceRequestAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'attendanceRequestAction';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Absen Manual')
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->modalHeading('Ajukan Absen Manual')
            ->modalDescription('Isi form berikut untuk mengajukan absen manual.')
            ->modalWidth('lg')
            ->schema([
                Select::make('type')
                    ->label('Jenis Absen')
                    ->options([
                        AttendanceStatus::CheckIn->value => 'Check In',
                        AttendanceStatus::CheckOut->value => 'Check Out',
                    ]),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->maxDate(Carbon::now()),
                TimePicker::make('requested_time')
                    ->label('Request Waktu'),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->rows(3)
                    ->maxLength(500),
                FileUpload::make('image_path')
                    ->label('Bukti Foto')
                    ->image()
                    ->directory('absen')
                    ->disk('s3')
                    ->preventFilePathTampering(
                        allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'absen/')
                    ),
            ])
            ->action(function (array $data): void {
                AttendanceRequest::create([
                    'user_id' => Auth::id(),
                    'type' => $data['type'],
                    'date' => $data['date'],
                    'requested_time' => $data['requested_time'],
                    'reason' => $data['reason'],
                    'image_path' => $data['image_path'],
                    'status' => ConfirmationStatus::Pending->value
                ]);
            })
            ->disabled(fn (): bool => $this->isAlreadyCheckedOut());
    }

    private function isAlreadyCheckedOut(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->whereNotNull('checkout_time')
            ->exists();
    }
}
