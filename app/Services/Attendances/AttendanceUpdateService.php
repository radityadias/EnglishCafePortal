<?php

namespace App\Services\Attendances;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\AttendanceRecap;
use Carbon\Carbon;

class AttendanceUpdateService
{
    public function isUserHaveRecap(?int $userId): bool
    {
        return AttendanceRecap::where('user_id', $userId)->exists();
    }

    public function isCheckoutTimeEmpty(?string $checkout): bool
    {
        return is_null($checkout);
    }

    public function storeAttendanceRecap(?int $userId): void
    {
        AttendanceRecap::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'total_hours' => 0,
            ]
        );
    }

    public function updateAttendanceRecap(?int $userId): void
    {
        $recap = AttendanceRecap::where('user_id', $userId)->first();

        if (! $recap) {
            return;
        }

        $data = $this->calculate($userId);

        $recap->update([
            'total_hours'    => $data->total_hours ?? $recap->total_hours,
            'sick_leaves'    => $data->leaves ?? $recap->sick_leaves,
            'absent_leaves'  => $data->absent ?? $recap->absent_leaves,
            'total_warnings' => $data->warnings ?? $recap->total_warnings,
        ]);
    }

    public function updateAttendance(Attendance $attendance): void
    {
        $attendance->updateQuietly([
            'working_time' => $this->calculateTimeDifference($attendance->checkin_time, $attendance->checkout_time),
        ]);
    }

    public function calculateTimeDifference(?string $checkin, ?string $checkout): ?float
    {
        if (is_null($checkin) || is_null($checkout)) {
            return null;
        }

        return Carbon::parse($checkin)->diffInMinutes(Carbon::parse($checkout));
    }

    private function calculate(?int $userId): ?object
    {
        return Attendance::where('user_id', $userId)
            ->selectRaw('
                SUM(working_time) as total_hours,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as leaves,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as absent,
                COUNT(CASE WHEN warning_level IS NOT NULL THEN 1 END) as warnings
            ', [
                AttendanceStatus::Leave->value,
                AttendanceStatus::Absent->value,
            ])
            ->first();
    }
}
