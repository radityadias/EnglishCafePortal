<?php

namespace App\Jobs;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class UpdateAttendanceAbsent implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $absent = $this->getAbsentUser();
        $leave = $this->getLeaveUser();

        $this->storeAttendanceAbsent($absent);
        $this->storeAttendanceLeave($leave);
    }

    public function getAbsentUser(): Collection
    {
        return User::whereDoesntHave('attendances', function ($query) {
            $query->whereDate('checkin_date', today());
        })
            ->whereDoesntHave('leaveRequest', function ($query) {
                $query->where('start_date', '<=', today())
                    ->where('end_date', '>=', today());
            })
            ->get();
    }

    public function getLeaveUser(): Collection
    {
        return User::whereHas('leaveRequest', function ($query) {
            $query->where('start_date', '<=', today())
                ->where('end_date', '>=', today())
                ->where('status', 'approved');
        })
            ->get();
    }

    public function storeAttendanceAbsent(Collection $users): void
    {
        foreach ($users as $user) {
            Attendance::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'checkin_date' => today(),
                ],
                [
                    'status' => AttendanceStatus::Absent,
                ]
            );
        }
    }

    public function storeAttendanceLeave(Collection $users): void
    {
        foreach ($users as $user) {
            Attendance::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'checkin_date' => today(),
                ],
                [
                    'status' => AttendanceStatus::Leave,
                ]
            );
        }

    }
}
