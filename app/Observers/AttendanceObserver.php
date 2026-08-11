<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Services\Attendances\AttendanceUpdateService;

class AttendanceObserver
{
    public AttendanceUpdateService $attendanceService;

    public function __construct(AttendanceUpdateService $attendanceUpdateService)
    {
        $this->attendanceService = $attendanceUpdateService;
    }

    public function created(Attendance $attendance): void
    {
        if (! $this->attendanceService->isUserHaveRecap($attendance->user_id)) {
            $this->attendanceService->storeAttendanceRecap($attendance->user_id);
        }
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        if (!$attendance->wasChanged('checkout_time')) {
            return;
        }

        if ($this->attendanceService->isCheckoutTimeEmpty($attendance->checkout_time)) {
            return;
        }

        $this->attendanceService->updateAttendance($attendance);
        $this->attendanceService->updateAttendanceRecap($attendance->user_id);
    }

    /**
     * Handle the Attendance "deleted" event.
     */
    public function deleted(Attendance $attendance): void
    {
        $this->attendanceService->updateAttendanceRecap($attendance->user_id);
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        $this->attendanceService->updateAttendanceRecap($attendance->user_id);
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        //
    }
}
