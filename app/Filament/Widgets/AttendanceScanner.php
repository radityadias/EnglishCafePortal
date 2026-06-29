<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\User;
use App\Services\GeofenceService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AttendanceScanner extends Widget
{
    protected string $view = 'filament.widgets.attendance-scanner';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    private const float ALLOWED_RADIUS = 50.0;
    private const LATITUDE = -7.8161472;
    private const LONGITUDE = 110.3935871;

    public bool $alreadyCheckedIn = false;

    public function mount(): void
    {
        $this->alreadyCheckedIn = $this->isAlreadyCheckedIn();
    }

    public function processAttendance(float $latitude, float $longitude): void
    {
        $user = $this->getAuthUser();

        if (!$user) {
            $this->sendNotification('error', __('attendance.error.title'), __('attendance.error.description'));
            return;
        }

        if ($this->alreadyCheckedIn) {
            $this->sendNotification('info', __('attendance.already.title'), __('attendance.already.description'));
            return;
        }

//        $branch = $user->employeeProfile?->branch;
//
//        if ($this->isBranchNotConfigured($branch)) {
//            $this->sendNotification('error', __('attendance.error.title'), __('attendance.error.description'));
//            return;
//        }

        $geofence = $this->instantiateGeofenceService();
        $distance = $geofence->calculateDistance($latitude, $longitude, self::LATITUDE, self::LONGITUDE);

        if ($distance > self::ALLOWED_RADIUS) {
            $this->sendNotification('danger', __('attendance.invalid.title'), __('attendance.invalid.description'));
            return;
        }

        $this->storeAttendance($user);
        $this->alreadyCheckedIn = true;

        $this->sendNotification('success', __('attendance.success.title'), __('attendance.success.description'));
    }

    private function getAuthUser(): ?User
    {
        return Auth::user();
    }

    private function isBranchNotConfigured($branch): bool
    {
        return !$branch || !$branch->latitude || !$branch->longitude;
    }

    private function isAlreadyCheckedIn(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->exists();
    }

    private function sendNotification(string $type, string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->status($type)
            ->send();
    }

    private function instantiateGeofenceService(): GeofenceService
    {
        return app(GeofenceService::class);
    }

    private function storeAttendance($user): void
    {
        // firstOrCreate guards against a duplicate insert if two requests
        // race past the alreadyCheckedIn check at nearly the same time.
        Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'checkin_date' => today(),
            ],
            [
                'checkin_time' => Carbon::now(),
            ]
        );
    }
}
