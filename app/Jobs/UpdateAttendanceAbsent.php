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
        $users = $this->getAbsentUser();

        $this->storeUserAttendance($users);
    }

    public function getAbsentUser(): Collection
    {
        return User::whereDoesntHave('attendances', function ($query) {
            $query->whereDate('checkin_date', today());
        })->get();
    }

    public function storeUserAttendance(Collection $users): void
    {
        foreach($users as $user) {
            Attendance::firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'checkin_date' => today(),
                    'status' => AttendanceStatus::Absent,
                ]
            );
        }
    }
}
