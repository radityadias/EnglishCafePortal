<?php

namespace App\Filament\Actions\Attendance;

use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class LeaveRequestAction extends Action
{
    public static function getDefaultName() : string
    {
        return 'leave_request';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Ajukan Izin')
            ->icon('heroicon-o-document-text')
            ->color('warning')
            ->modalHeading('Ajukan Izin / Cuti')
            ->modalDescription('Isi form berikut untuk mengajukan izin atau cuti.')
            ->modalWidth('lg')
            ->schema([
                Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'sick' => 'Sakit',
                        'leave' => 'Cuti',
                        'personal' => 'Keperluan Pribadi',
                        'other' => 'Lainnya',
                    ])
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->minDate(today()),

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->minDate(today())
                    ->afterOrEqual('start_date'),

                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->rows(3)
                    ->maxLength(500),
                FileUpload::make('image')
                    ->label('Bukti Foto')
                    ->image()
                    ->required()
                    ->directory('absen')
                    ->disk('s3')
                    ->preventFilePathTampering(
                        allowFilePathUsing: fn(string $file): bool => str_starts_with($file, 'absen/')
                    ),
            ])
            ->action(function (array $data): void { $this->storeLeaveRequest($data); })
            ->disabled(fn (): bool => $this->isLeaveRequestExist());
    }

    private function isLeaveRequestExist(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->leaveRequest()
                ->where('start_date', '<=', today())
                ->where('end_date', '>=', today())
                ->exists();
    }

    private function storeLeaveRequest($data): void
    {
        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'image' => $data['image'],
        ]);
    }
}


