<?php

namespace App\Filament\Resources\Users\Actions;

use App\Jobs\SendEmailSetupPassword;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SendSetupLinkAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'resend_setup_link';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Kirim Ulang Link Setup')
            ->icon('heroicon-o-envelope')
            ->color('warning')
            ->visible(fn (User $record) => !$record->hasSetPassword())
            ->requiresConfirmation()
            ->modalDescription('Email berisi link untuk mengatur password akan dikirim ulang ke karyawan ini.')
            ->action(function (User $record) {
                SendEmailSetupPassword::dispatch($record);

                Notification::make()
                    ->title('Link setup berhasil dikirim ulang.')
                    ->success()
                    ->send();
            });
    }
}
