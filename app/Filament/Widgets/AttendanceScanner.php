<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\GeofenceService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AttendanceScanner extends Widget implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.widgets.attendance-scanner';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

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
            ->modalWidth('xl')
            ->schema([
                Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'sick'      => 'Sakit',
                        'leave'     => 'Cuti',
                        'personal'  => 'Keperluan Pribadi',
                        'other'     => 'Lainnya',
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
            ])
            ->action(function (array $data): void {
                LeaveRequest::create([
                    'user_id'    => Auth::id(),
                    'type'       => $data['type'],
                    'start_date' => $data['start_date'],
                    'end_date'   => $data['end_date'],
                    'reason'     => $data['reason'],
                    'status'     => 'pending',
                ]);
            });
    }
    public function processAttendance(float $latitude, float $longitude): void
    {
        $user = $this->getAuthUser();

        if (!$user) {
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

        if ($attendance->wasRecentlyCreated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description'));
        } else {
            $this->sendNotification('info', __('notification.existed_title'), __('notification.existed_description'));
        }

    }

    private function processCheckOut(User $user): void
    {
        $updated = $this->updateAttendance($user);

        $this->alreadyCheckedOut = true;

        if ($updated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description'));
        } else {
            $this->sendNotification('info', __('notification.existed_title', ['name' => $user->name, 'time' => Carbon::now()]), __('notification.existed_description'));
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

    private function instantiateGeofenceService(): GeofenceService
    {
        return app(GeofenceService::class);
    }

    private function storeAttendance($user): Attendance
    {
        $checkin_time = Carbon::now();

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
        $updated = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->whereNull('checkout_time')
            ->update(['checkout_time' => Carbon::now()]);

        return $updated > 0;
    }

    private function checkAttendanceStatus(User $user, Carbon $checkin_time): AttendanceStatus
    {
        $work_time = $user->employeeProfile?->work_time_start;

        if (!$work_time) {
            return AttendanceStatus::Attend;
        }

        return $checkin_time->gt(Carbon::parse($work_time))
            ? AttendanceStatus::Late
            : AttendanceStatus::Attend;
    }
}
