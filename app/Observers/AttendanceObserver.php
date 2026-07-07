<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\AttendanceRecap;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        if (!$this->isUserHaveRecap($attendance)) {
            $this->storeAttendanceRecap($attendance);
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

        if ($this->isCheckoutTimeEmpty($attendance)) {
            return;
        }

        $this->updateAttendance($attendance);
        $this->updateAttendanceRecap($attendance);
    }

    /**
     * Handle the Attendance "deleted" event.
     */
    public function deleted(Attendance $attendance): void
    {
        //
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        //
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        //
    }

    public function isUserHaveRecap(Attendance $attendance): bool
    {
       return AttendanceRecap::where('user_id', $attendance->user_id)->exists();
    }

    public function isCheckoutTimeEmpty(Attendance $attendance): bool
    {
       return is_null($attendance->checkout_time);
    }

    public function isUserLeave(): bool
    {
        //
    }

    public function storeAttendanceRecap(Attendance $attendance): void
    {
       AttendanceRecap::firstOrCreate(
           [
               'user_id' => $attendance->user_id
           ],
           [
               'total_hours' => 0
           ]
       );
    }

    public function updateAttendanceRecap(Attendance $attendance): void
    {
        $recap = AttendanceRecap::where('user_id', $attendance->user_id)->first();

        $recap->update([
            'total_hours' => $this->calculateTotalHours($attendance),
        ]);
    }

    public function updateAttendance(Attendance $attendance): void
    {
        $attendance->updateQuietly([
            'working_time' => $this->calculateTimeDifference($attendance)
        ]);
    }

    public function calculateTimeDifference(Attendance $attendance): float
    {
        return Carbon::parse($attendance->checkin_time)->diffInMinutes($attendance->checkout_time);
    }

    public function calculateTotalHours(Attendance $attendance): float
    {
        return Attendance::where('user_id', $attendance->user_id)->sum('working_time');
    }
}
