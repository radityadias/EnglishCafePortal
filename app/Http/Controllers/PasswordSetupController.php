<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordSetupController extends Controller
{
    public function showForm(string $token, Request $request): View|RedirectResponse
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Link tidak valid atau sudah kedaluwarsa. Hubungi administrator.']);
        }

        if ($user->hasSetPassword()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Password sudah pernah diatur. Silakan login.']);
        }

        return view('auth.password-setup', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'        => Hash::make($password),
                    'password_set_at' => now(),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('filament.admin.auth.login')
                ->with('success', 'Password berhasil diatur. Silakan login.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
