<?php

namespace App\FilamentAuth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as FilamentLoginResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements FilamentLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = Filament::auth()->user();

        if (!$user) {
            return redirect('/app/login'); // Redirect back to login if no user is found
        }

        // Check user role using Spatie's method
        if ($user->hasRole('super_admin')) {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/app');
        }
    }
}
