<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Pages\SimplePage;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rules\Password as PasswordRule;

class SetupPassword extends SimplePage implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.auth.setup-password';
    protected static bool $shouldRegisterNavigation = false;

    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(Request $request): void
    {
        $this->token = $request->route('token');
        $this->email = $request->query('email', '');

        $user = $this->getUser();

        if ($this->isInvalid($user)) {
            $this->sendNotification('danger', __('auth.invalid_title'), __('auth.invalid_description'));

            $this->redirect('/');
            return;
        }

        if ($user->hasSetPassword()) {
            $this->redirect('/');
            return;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->rule(PasswordRule::defaults()),

            TextInput::make('password_confirmation')
                ->label('Konfirmasi Password')
                ->password()
                ->required()
                ->same('password'),
        ]);
    }

    public function setupAction(): Action
    {
        return Action::make('setup')
            ->label('Aktifkan Akun')
            ->action(function (): void {
                $this->validate();

                $status = $this->handleResetPassword();

                if ($this->isResetSuccess($status)) {
                    $this->sendNotification('success', 'auth.password_title', 'auth.password_description');

                    $this->redirect('/login');
                    return;
                }

                Notification::make()
                    ->title(__($status))
                    ->danger()
                    ->send();
            });
    }

    private function handleResetPassword(): string
    {
        return Password::broker()->reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password'        => Hash::make($password),
                    'password_set_at' => now(),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }

    public function getTitle(): string
    {
        return 'Atur Password Anda';
    }

    private function getUser(): ?User
    {
        return User::where('email', $this->email)->first();
    }

    private function isInvalid($user): bool
    {
        return !$user || !Password::broker()->tokenExists($user, $this->token);
    }

    private function isResetSuccess($status): bool
    {
        return $status === Password::PASSWORD_RESET;
    }

    private function sendNotification(string $type, string $title, string $description): void
    {
        Notification::make()
            ->title($title)
            ->body($description)
            ->status($type)
            ->send();
    }
}
