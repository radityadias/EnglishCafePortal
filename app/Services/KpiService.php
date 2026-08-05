<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\ClassType;
use App\Enums\Division;
use App\Enums\KpiStatus;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Div;

class KpiService
{
    private const float TARGET_ATTENDANCE = 90.0;
    private const float TARGET_CLOSING_RATE = 70.0;
    private const int TARGET_THEORY_CLASS = 20;
    private const int TARGET_ALL_CLASS = 70;
    private const float TARGET_PROFIT = 10000000;

    public bool $isAchieved = false;

    public function calculate(?int $userId, ?int $year, ?int $month): array
    {
        $year ??= now()->year;
        $month ??= now()->month;

        return [
            'attendance_percentage' => $this->calculateAttendancePercentage($userId, $year, $month),
            'theory_class_count' => $this->getTheoryClassCount($userId, $year, $month),
            'dt_class_count' => $this->getDailyTalkClassCount($userId, $year, $month),
            'profit' => $this->getTotalRevenue($userId, $year, $month),
            'closing_rate' => $this->calculateClosingRatePercentage($userId, $year, $month),
        ];
    }

    private function getCountedAttendance(?int $userId, ?int $year, ?int $month): ?int
    {
        return Attendance::where('user_id', $userId)
            ->where('status', [AttendanceStatus::Attend, AttendanceStatus::Late])
            ->whereYear('checkin_date', $year)
            ->whereMonth('checkin_date', $month)
            ->count();
    }

    private function getDays(?int $year, ?int $month): int
    {
        return Carbon::create($year, $month)->daysInMonth;
    }

    private function getTheoryClassCount(?int $userId, ?int $year, ?int $month): ?int
    {
        return ClassSession::where('user_id', $userId)
            ->where('type', ClassType::Theory)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();
    }

    private function getDailyTalkClassCount(?int $userId, ?int $year, ?int $month): ?int
    {
        return ClassSession::where('user_id', $userId)
            ->where('type', ClassType::DailyTalk)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();
    }

    private function getTotalRevenue(?int $userId, ?int $year, ?int $month): ?float
    {
        return Appointment::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('payment_amount');
    }

    private function getCountedAppointment(?int $userId, ?int $year, ?int $month): ?int
    {
        return Appointment::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
    }

    private function getCountedSuccessAppointment(?int $userId, ?int $year, ?int $month): ?int
    {
        return Appointment::where('user_id', $userId)
            ->where('is_success', true)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
    }

    private function calculateAttendancePercentage(?int $userId, ?int $year, ?int $month): float
    {
        $attendance = $this->getCountedAttendance($userId, $year, $month);
        $monthDays = $this->getDays($year, $month);

        $result = $attendance === 0 ? 0.0 : $attendance / $monthDays * 100;

        return $this->getFormattedPercentage($result);
    }

    private function calculateClosingRatePercentage(?int $userId, ?int $year, ?int $month): float
    {
        $appointment = $this->getCountedAppointment($userId, $year, $month);
        $success = $this->getCountedSuccessAppointment($userId, $year, $month);

        $result = $appointment === 0 ? 0.0 : $success / $appointment * 100;

        return $this->getFormattedPercentage($result);
    }

    private function getFormattedPercentage(?float $value): float
    {
        return round($value ?? 0.0, 1);
    }

    public function getStatus(?User $user, array $kpi): ?string
    {
        if ($this->isMasterChef($user)) {
            return $this->calculateMasterChefIndex($kpi);
        };

        return $this->calculateOperationalChef($kpi);
    }

    private function isMasterChef(?User $user): bool
    {
        return $user->employeeProfile?->division === Division::MasterChef
            || $user->internshipProfile?->division == Division::MasterChef;
    }

    private function calculateMasterChefIndex(?array $kpi): ?string
    {
        $isAttendanceAchieved = ($kpi['attendance_percentage'] >= self::TARGET_ATTENDANCE);
        $isAllClassAchieved = (($kpi['theory_class_count'] + $kpi['dt_class_count'] >= self::TARGET_ALL_CLASS));

        if ($isAttendanceAchieved && $isAllClassAchieved) {
            $this->isAchieved = true;
            return KpiStatus::Achieved->getLabel();
        }

        $this->isAchieved = false;
        return KpiStatus::NotAchieved->getLabel();
    }

    private function calculateOperationalChef(?array $kpi): ?string
    {
        $isAttendanceAchieved = ($kpi['attendance_percentage'] >= self::TARGET_ATTENDANCE);
        $isTheoryClassAchieved = ($kpi['theory_class_count'] >= self::TARGET_THEORY_CLASS);
        $isClosingRateAchieved = ($kpi['closing_rate'] >= self::TARGET_CLOSING_RATE);
        $isProfitAchieved = ($kpi['profit'] >= self::TARGET_PROFIT);

        if ($isAttendanceAchieved && $isTheoryClassAchieved && $isClosingRateAchieved && $isProfitAchieved) {
            $this->isAchieved = true;
            return KpiStatus::Achieved->getLabel();
        }

        $this->isAchieved = false;
        return KpiStatus::NotAchieved->getLabel();
    }
}
