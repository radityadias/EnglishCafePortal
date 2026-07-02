<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $setupUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang — Atur Password Akun Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-setup',
            with: [
                'user'           => $this->user,
                'setupUrl'       => $this->setupUrl,
                'expiresInHours' => config('auth.passwords.users.expire') / 60,
            ],
        );
    }
}
