<?php

namespace App\Jobs;

use App\Mail\PasswordSetupMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendEmailSetupPassword implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly User $user,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->hasSetPassword()) {
            return;
        }

        $token = Password::broker()->createToken($this->user);

        $setupUrl = route('password.setup', [
            'token' => $token,
            'email' => $this->user->email
        ]);

        Mail::to($this->user->email)
            ->send(new PasswordSetupMail($this->user, $setupUrl));
    }
}
