<?php

namespace App\Filament\Actions\Attendance;

use App\Enums\ClassType;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ClassSessionReportAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'classSessionReportAction';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Laporan Kelas')
            ->icon(Heroicon::Bookmark)
            ->color('primary')
            ->modalHeading('Laporan Kelas')
            ->modalDescription('Isi form berikut untuk membuat laporan kelas Dailty Talk/Teori')
            ->modalWidth('xl')
            ->schema([
                Select::make('type')
                    ->label('Jenis Kelas')
                    ->options([
                        ClassType::DailyTalk->value => 'Daily Talk',
                        ClassType::Teory->value => 'Teori',
                    ])
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Waktu Selesai'),
                Textarea::make('notes')
                    ->label('Catatan')
            ])
            ->action(function (array $data): void {$this->handleClassSession($data);});
    }

    public function handleClassSession($data): void
    {
        $user = $this->getUser();
        $attendance = $this->getTodayAttendance($user);

        if ($attendance) {
            $this->storeClassSession($user, $attendance, $data);
        }
    }

    public function storeClassSession(User $user, Attendance $attendance, $data): void
    {
        ClassSession::firstOrCreate([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'type' => $data['type'],
            'date' => now(),
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'notes' => $data['notes'],
        ]);
    }

    private function getUser(): ?User
    {
        return Auth::user();
    }

    private function getTodayAttendance(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->first();
    }
}
