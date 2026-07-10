<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use App\Enums\Position;
use App\Models\Attendance;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\AttendanceRequest;
use App\Services\GeofenceService;
use Carbon\Carbon;
use Filament\Forms\Components\FileUpload;
use App\Enums\ConfirmationStatus;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;

class AttendanceScanner extends Widget implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.widgets.attendance-scanner';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    private const float ALLOWED_RADIUS = 50.0;
    private const float LATITUDE = -7.8161472;
    private const float LONGITUDE = 110.3935871;

    public bool $alreadyCheckedIn = false;
    public bool $alreadyCheckedOut = false;

    public function mount(): void
    {
        $this->alreadyCheckedIn = $this->isAlreadyCheckedIn();
        $this->alreadyCheckedOut = $this->isAlreadyCheckedOut();
    }

    public function leaveRequestAction(): Action
    {
        return Action::make('leaveRequest')
            ->label('Ajukan Izin')
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
            ->action(function (array $data): void {
                LeaveRequest::create([
                    'user_id' => Auth::id(),
                    'type' => $data['type'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'reason' => $data['reason'],
                    'status' => 'pending',
                    'image' => $data['image'],
                ]);
            })
            ->disabled(fn(): bool => $this->isLeaveRequestExist($this->getAuthUser()));
    }

    public function attendanceRequestAction(): Action
    {
        return Action::make('attendanceRequest')
            ->label('Absen Manual')
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
            ->disabled(fn (): bool => $this->alreadyCheckedOut);
    }

    public function processAttendance(float $latitude, float $longitude): void
    {
        $user = $this->getAuthUser();

        if (!$user) {
            $this->sendNotification('error', __('notification.error_title'), __('notification.error_description'));
            return;
        }

        if ($this->isBranchNotConfigured($user))
        {
            $this->sendNotification('error', __('notification.error_title'), __('notification.error_description'));
            return;
        }

        $distance = $this->instantiateGeofenceService()
            ->calculateDistance($latitude, $longitude, self::LATITUDE, self::LONGITUDE);

        if ($distance > self::ALLOWED_RADIUS) {
            $this->sendNotification('danger', __('notification.invalid_title'), __('notification.invalid_description'));
            return;
        }

        // Check Out
        if ($this->alreadyCheckedIn && !$this->alreadyCheckedOut) {
            $this->processCheckOut($user);
            return;
        }

        // Check In
        if (!$this->alreadyCheckedIn) {
            $this->processCheckin($user);
            return;
        }

    }

    private function processCheckin(User $user): void
    {
        $attendance = $this->storeAttendance($user);

        $this->alreadyCheckedIn = true;
        $this->refreshTable();

        if ($attendance->wasRecentlyCreated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description', ['name' => $user->name, '']));
        } else {
            $this->sendNotification('info', __('notification.existed_title'), __('notification.existed_description'));
        }

    }

    private function processCheckOut(User $user): void
    {
        $updated = $this->updateAttendance($user);

        $this->alreadyCheckedOut = true;
        $this->refreshTable();

        if ($updated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description', ['name' => $user->name, 'checkin_time' => Carbon::now()->format('H:i')]));
        } else {
            $this->sendNotification('info', __('notification.existed_title'), __('notification.existed_description'));
        }
    }

    private function getAuthUser(): ?User
    {
        return Auth::user();
    }

    private function isBranchNotConfigured($branch): bool
    {
        return !$branch || !$branch->latitude || !$branch->longitude;
    }

    private function isLeaveRequestExist($user): bool
    {
        return $user->leaveRequest()
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->exists();
    }

    private function isEmployee($user): bool
    {
        return $user->position === Position::Employee;
    }

    private function isAlreadyCheckedIn(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->exists();
    }

    private function isAlreadyCheckedOut(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->whereNotNull('checkout_time')
            ->exists();
    }

    private function sendNotification(string $type, string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->status($type)
            ->send();
    }

    private function refreshTable(): void
    {
        $this->dispatch('attendance_scanned');
    }

    private function instantiateGeofenceService(): GeofenceService
    {
        return app(GeofenceService::class);
    }

        private function checkAttendanceStatus(User $user,Carbon $checkin_time): AttendanceStatus
    {
        if ($this->isEmployee($user)) {
            $workTimeStart = $user->employeeProfile?->work_time_start;

            if (!$workTimeStart) {
                return AttendanceStatus::Attend;
            }

            return $checkin_time->gt(Carbon::parse($workTimeStart))
                ? AttendanceStatus::Late
                : AttendanceStatus::Attend;
        }

        return AttendanceStatus::Attend;
    }

    private function storeAttendance($user): Attendance
    {
        $checkin_time = Carbon::now();
        // firstOrCreate guards against a duplicate insert if two requests
        // race past the alreadyCheckedIn check at nearly the same time.
        return Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'checkin_date' => today(),
            ],
            [
                'checkin_time' => $checkin_time,
                'status' => $this->checkAttendanceStatus($user, $checkin_time),
            ]
        );
    }

    private function updateAttendance($user): bool
    {
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->whereNull('checkout_time')
            ->first();

        if (!$attendance) {
            return false;
        }

        $attendance->update(['checkout_time' => Carbon::now()]);

        return true;
    }
}
