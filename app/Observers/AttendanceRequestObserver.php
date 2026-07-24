<?php

namespace App\Observers;

use App\Enums\AttendanceStatus;
use App\Enums\ConfirmationStatus;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\User;

class AttendanceRequestObserver
{
    /**
     * Handle the AttendanceRequest "created" event.
     */
    public function created(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    /**
     * Handle the AttendanceRequest "updated" event.
     */
    public function updated(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    /**
     * Handle the AttendanceRequest "deleted" event.
     */
    public function deleted(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    /**
     * Handle the AttendanceRequest "restored" event.
     */
    public function restored(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    /**
     * Handle the AttendanceRequest "force deleted" event.
     */
    public function forceDeleted(AttendanceRequest $attendanceRequest): void
    {
        //
    }


    public function handleAttendance(AttendanceRequest $attendanceRequest): void
    {
       //
    }
}
