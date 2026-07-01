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
    private const float LATITUDE = -7.8161472;
    private const float LONGITUDE = 110.3935871;

    public bool $alreadyCheckedIn = false;
    public bool $alreadyCheckedOut = false;

    public function mount(): void
    {
        $this->alreadyCheckedIn = $this->isAlreadyCheckedIn();
        $this->alreadyCheckedOut = $this->isAlreadyCheckedOut();
    }

    public function processAttendance(float $latitude, float $longitude): void
    {
        $user = $this->getAuthUser();

        if (!$user) {
            $this->sendNotification('error', __('notification.error_title'), __('notification.error_description'));
            return;
        }

        $distance = $this->instantiateGeofenceService()
            ->calculateDistance($latitude, $longitude, self::LATITUDE, self::LONGITUDE);

        if ($distance > self::ALLOWED_RADIUS) {
            $this->sendNotification('danger', __('notification.invalid_title'), __('notification.invalid_description'));
            return;
        }

        // Check Out
        if ($this->alreadyCheckedIn && !$this->alreadyCheckedOut) {
            $this->processCheckOut($user);
        }

        // Check In
        if (!$this->alreadyCheckedIn) {
            $this->processCheckin($user);
        }

        $this->sendNotification('success', __('notification.success_title'), __('notification.success_description'));
    }

    private function processCheckin(User $user): void
    {
        $attendance = $this->storeAttendance($user);

        $this->alreadyCheckedIn = true;

        if ($attendance->wasRecentlyCreated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description'));
        } else {
            $this->sendNotification('info', __('notification.existed_title'), __('notification.existed_description'));
        }

    }

    private function processCheckOut(User $user): void
    {
        $updated = $this->updateAttendance($user);

        $this->alreadyCheckedOut = true;

        if ($updated) {
            $this->sendNotification('success', __('notification.success_title'), __('notification.success_description'));
        } else {
            $this->sendNotification('info', __('notification.existed_title', ['name' => $user->name, 'time' => Carbon::now()]), __('notification.existed_description'));
        }
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

    private function isAlreadyCheckedOut(): bool
    {
        return Attendance::where('user_id', Auth::id())
            ->whereDate('checkin_date', today())
            ->whereNotNull('checkout_time')
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

    private function storeAttendance($user): Attendance
    {
        // firstOrCreate guards against a duplicate insert if two requests
        // race past the alreadyCheckedIn check at nearly the same time.
        return Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'checkin_date' => today(),
            ],
            [
                'checkin_time' => Carbon::now(),
            ]
        );
    }

    private function updateAttendance($user): bool
    {
        $updated = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->whereNull('checkout_time')
            ->update(['checkout_time' => Carbon::now()]);

        return $updated > 0;
    }
}
