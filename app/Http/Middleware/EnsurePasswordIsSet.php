<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordIsSet
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->hasSetPassword()) {
            Auth::logout();

            return redirect('/login')
                ->withErrors(['email' => 'Akun Anda belum diaktifkan. Silakan cek email Anda.']);
        }

        return $next($request);
    }
}
