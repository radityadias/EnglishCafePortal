<?php

namespace App\Services;

use Filament\Notifications\Notification;

class NotificationService
{
    public function successNotification(string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->success()
            ->send();
    }

    public function errorNotification(string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->danger()
            ->send();
    }

    public function infoNotification(string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->info()
            ->send();
    }

    public function warningNotification(string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->warning()
            ->send();
    }
}
