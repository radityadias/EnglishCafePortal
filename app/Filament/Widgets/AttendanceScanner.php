<?php

namespace App\Filament\Widgets;

use App\Filament\Actions\Attendance\AttendanceRequestAction;
use App\Filament\Actions\Attendance\LeaveRequestAction;
use App\Services\AttendanceScannerService;
use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;

class AttendanceScanner extends Widget implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.widgets.attendance-scanner';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected AttendanceScannerService $attendanceService;

    public bool $alreadyCheckedIn = false;
    public bool $alreadyCheckedOut = false;

    public function boot(AttendanceScannerService $service): void
    {
        $this->attendanceService = $service;
    }

    public function mount(): void
    {
        $this->alreadyCheckedIn = $this->attendanceService->isAlreadyCheckedIn();
        $this->alreadyCheckedOut = $this->attendanceService->isAlreadyCheckedOut();
    }

    public function leaveRequestAction(): Action
    {
        return LeaveRequestAction::make();
    }

    public function attendanceRequestAction(): Action
    {
        return AttendanceRequestAction::make();
    }

    public function processAttendance(float $latitude, float $longitude): void
    {
        $this->attendanceService->handleAttendance($latitude, $longitude);

        $this->alreadyCheckedIn = $this->attendanceService->isAlreadyCheckedIn();
        $this->alreadyCheckedOut = $this->attendanceService->isAlreadyCheckedOut();
        $this->refreshTable();
    }

    private function refreshTable(): void
    {
        $this->dispatch('attendance_scanned');
    }

}
