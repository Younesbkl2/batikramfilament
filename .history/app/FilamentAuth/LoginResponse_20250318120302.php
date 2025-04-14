<?php

namespace App\FilamentAuth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as FilamentLoginResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements FilamentLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = Filament::auth()->user(); // Ensure correct authentication method

        if (!$user) {
            return redirect('/app/login'); // Fallback in case no user is authenticated
        }

        // Redirect based on role
        if ($user->HasRole('super_admin')) {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/app');
        }
    }
}
