<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\LoginResponse;
use Illuminate\Http\Request;

class CustomLogin extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('Username / Email'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $login_type => $data['login'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    // Ensure method signature matches the parent class
    protected function authenticate(Request $request)
    {
        $request->validate([
            'data.login' => ['required'],
            'data.password' => ['required'],
        ]);

        $credentials = $this->getCredentialsFromFormData($request->input('data'));

        // Use the Auth facade properly to attempt login
        if (! Auth::attempt($credentials, $request->boolean('data.remember'))) {
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        // Return the login response
        return app(LoginResponse::class);
    }
}
