<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\Position;
use App\Enums\WorkType;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceScannerService
{
    private NotificationService $notificationService;
    private const float ALLOWED_RADIUS = 100.0;

    public function __construct(NotificationService $service)
    {
        $this->notificationService = $service;
    }

    public function handleAttendance($latitude, $longitude): void
    {
        $user = $this->getAuthUser();

        if (!$this->isUserExist()) {
            $this->notificationService->errorNotification(__('notification.error_title'), __('notification.error_description'));
            return;
        }

        if (!$this->isBranchConfigured($user))
        {
            $this->notificationService->errorNotification(__('notification.branch_title'), __('notification.branch_description'));
            return;
        }

        if (!$this->isWorkTimeConfigured($user))
        {
            $this->notificationService->errorNotification(__('notification.work_time_title'), __('notification.work_time_description'));
            return;
        }

        $distance = $this->instantiateGeofenceService()->calculateDistance($latitude, $longitude, $this->getUserBranchLatitude($user), $this->getUserBranchLongitude($user));

        if (!$this->isPositionValid($distance)) {
            $this->notificationService->errorNotification(__('notification.invalid_title'), __('notification.invalid_description'));
            return;
        }

        if ($this->canCheckin()) {
            $this->processCheckin($user);
            return;
        }

        if ($this->canCheckout()) {
            $this->processCheckOut($user);
            return;
        }

    }

    private function processCheckin(User $user): void
    {
        $attendance = $this->storeAttendance($user);

        if ($attendance->wasRecentlyCreated) {
            $this->notificationService->successNotification(__('notification.success_title'), __('notification.success_description', ['name' => $user->name, 'checkin_time' => Carbon::now()->format('H:i')]));
        } else {
            $this->notificationService->infoNotification(__('notification.existed_title'), __('notification.existed_description'));
        }
    }

    private function processCheckOut(User $user): void
    {
        $updated = $this->updateAttendance($user);

        if ($updated) {
            $this->notificationService->successNotification(__('notification.success_title'), __('notification.success_description', ['name' => $user->name, 'checkin_time' => Carbon::now()->format('H:i')]));
        } else {
            $this->notificationService->infoNotification(__('notification.existed_title'), __('notification.existed_description'));
        }
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

    private function checkAttendanceStatus(User $user, Carbon $checkin_time): AttendanceStatus
    {
        if ($this->isEmployee($user)) {
            if ($this->getWorkType($user) === WorkType::Fixed) {
                $workTimeStart = $user->employeeProfile?->work_time_start;

                if (!$workTimeStart) {
                    return AttendanceStatus::Attend;
                }

                return $checkin_time->gt(Carbon::parse($workTimeStart))
                    ? AttendanceStatus::Late
                    : AttendanceStatus::Attend;
            }
        }

        return AttendanceStatus::Attend;
    }

    private function getWorkType(User $user): ?WorkType
    {
        $workType = $user->employeeProfile?->work_type
            ?? $user->internshipProfile?->work_type;

        if ($workType instanceof WorkType) {
            return $workType;
        }

        return $workType ? WorkType::tryFrom($workType) : null;
    }

    private function isEmployee($user): bool
    {
        return $user->position === Position::Employee;
    }

    public function isDivisionChef($user): bool
    {
        return in_array($user->division?->name, [
            'Master Chef',
            'Chef Operational'
        ], true);
    }

    public function isAlreadyCheckedIn(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->exists();
    }

    public function isAlreadyCheckedOut(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->whereNotNull('checkout_time')
            ->exists();
    }

    private function getAuthUser(): ?User
    {
        return Auth::user()?->load([
            'employeeProfile.branch',
            'internshipProfile.branch',
        ]);
    }

    private function getUserBranchLatitude($user): ?float
    {
        return $user->employeeProfile?->branch?->latitude ?? $user->internshipProfile?->branch?->latitude;
    }

    private function getUserBranchLongitude($user): ?float
    {
        return $user->employeeProfile?->branch?->longitude ?? $user->internshipProfile?->branch?->longitude;
    }

    private function isUserExist(): bool
    {
        return Auth::check();
    }

    private function isBranchConfigured($user): bool
    {
        $lat = $this->getUserBranchLatitude($user);
        $lng = $this->getUserBranchLongitude($user);

        return $lat !== null && $lng !== null;
    }

    private function isWorkTimeConfigured($user): bool
    {
        if ($this->getWorkType($user) !== WorkType::Fixed) {
            return true;
        }

        return !is_null($user->employeeProfile?->work_time_start ?? $user->internshipProfile?->work_time_start);
    }

    private function isPositionValid($distance): bool
    {
        return $distance < self::ALLOWED_RADIUS;
    }

    private function canCheckin(): bool
    {
        return !$this->isAlreadyCheckedIn();
    }

    private function canCheckout(): bool
    {
        return $this->isAlreadyCheckedIn() && !$this->isAlreadyCheckedOut();
    }

    private function instantiateGeofenceService(): GeofenceService
    {
        return app(GeofenceService::class);
    }
}
