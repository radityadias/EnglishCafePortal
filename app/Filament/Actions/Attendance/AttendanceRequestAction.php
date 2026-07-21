<?php

namespace App\Filament\Actions\Attendance;

use App\Enums\ConfirmationStatus;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class AttendanceRequestAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'attendanceReqeustAction';
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
                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->rows(3)
                    ->maxLength(500),

                FileUpload::make('image_path')
                    ->label('Bukti Foto')
                    ->image()
                    ->required()
                    ->directory('absen')
                    ->disk('s3')
                    ->preventFilePathTampering(
                        allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'absen/')
                    )
                ,
            ])
            ->action(function (array $data): void {
                AttendanceRequest::create([
                    'user_id' => Auth::id(),
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
