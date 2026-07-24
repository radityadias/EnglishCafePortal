<?php

namespace App\Observers;

use App\Enums\AttendanceStatus;
use App\Enums\ConfirmationStatus;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRequestObserver
{
    public function created(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    public function updated(AttendanceRequest $attendanceRequest): void
    {
        if ($this->wasStatusChanged($attendanceRequest)) {
            $this->handleAttendance($attendanceRequest);
        }
    }

    public function deleted(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    public function restored(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    public function forceDeleted(AttendanceRequest $attendanceRequest): void
    {
        //
    }

    private function wasStatusChanged(AttendanceRequest $attendanceRequest): bool
    {
        return $attendanceRequest->wasChanged('status');
    }

    private function isRequestApproved(AttendanceRequest $attendanceRequest): bool
    {
        return $attendanceRequest->status === ConfirmationStatus::Approved;
    }

    private function isCheckinOnly(Attendance $attendance): bool
    {
        return !is_null($attendance->checkin_time)
            && is_null($attendance->checkout_time);
    }

    private function getUserAttendance(AttendanceRequest $attendanceRequest, User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', $attendanceRequest->date)
            ->first();
    }

    private function hasExistingAttendance(AttendanceRequest $attendanceRequest, User $user): bool
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', $attendanceRequest->date)
            ->exists();
    }

    private function calculateWorkingMinutes(Attendance $attendance, AttendanceRequest $attendanceRequest): int
    {
        return Carbon::parse($attendance->checkin_time)
            ->diffInMinutes(Carbon::parse($attendanceRequest->requested_time));
    }

    private function checkAttendanceStatus(User $user, $time)
    {
        $workTimeStart = $user->employeeProfile?->work_time_start ?? $user->internshipProfile?->work_time_start;

        if (!$workTimeStart) {
            return AttendanceStatus::Attend;
        }

        return $time->gt(Carbon::parse($workTimeStart))
            ? AttendanceStatus::Late
            : AttendanceStatus::Attend;
    }

    private function createAttendance(AttendanceRequest $attendanceRequest, User $user): void
    {
        Attendance::create([
            'user_id'      => $user->id,
            'checkin_date' => $attendanceRequest->date,
            'checkin_time' => $attendanceRequest->requested_time,
            'status'       => $this->checkAttendanceStatus($user, $attendanceRequest->requested_time),
        ]);
    }

    private function updateCheckout(Attendance $attendance, AttendanceRequest $attendanceRequest): void
    {
        $attendance->updateQuietly([
            'status'        => AttendanceStatus::Attend,
            'checkout_time' => $attendanceRequest->requested_time,
            'working_time'  => $this->calculateWorkingMinutes($attendance, $attendanceRequest),
        ]);
    }

    private function updateRecapAfterCheckout(Attendance $attendance): void
    {
        $recap = $attendance->user?->attendanceRecap;

        if (!$recap) {
            return;
        }

        $totalMinutes = Attendance::where('user_id', $attendance->user_id)
            ->sum('working_time');

        $recap->updateQuietly([
            'total_hours' => $totalMinutes,
        ]);
    }

    private function handleExistingAttendance(Attendance $attendance, AttendanceRequest $attendanceRequest): void
    {
        if (!$this->isCheckinOnly($attendance)) {
            return;
        }

        $this->updateCheckout($attendance, $attendanceRequest);
        $this->updateRecapAfterCheckout($attendance);
    }

    private function storeAttendance(AttendanceRequest $attendanceRequest): void
    {
        $user = $attendanceRequest->user;
        $existingAttendance = $this->getUserAttendance($attendanceRequest, $user);

        if ($existingAttendance) {
            $this->handleExistingAttendance($existingAttendance, $attendanceRequest);
            return;
        }

        $this->createAttendance($attendanceRequest, $user);
    }

    private function handleAttendance(AttendanceRequest $attendanceRequest): void
    {
        if ($this->isRequestApproved($attendanceRequest)) {
            $this->storeAttendance($attendanceRequest);
        }
    }
}
