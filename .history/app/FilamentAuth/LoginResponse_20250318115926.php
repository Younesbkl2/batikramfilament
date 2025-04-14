<?php

namespace App\FilamentAuth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as FilamentLoginResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements FilamentLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = auth()->user();

        // Redirect based on role
        if ($user->hasRole('super_admin')) {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/app');
        }
    }
}
