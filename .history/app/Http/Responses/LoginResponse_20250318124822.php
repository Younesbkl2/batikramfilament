<?php

namespace App\Http\Responses;

 
use App\Filament\Resources\AchatResource;
use App\Filament\Resources\OrderResource;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
 
class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // You can use the Filament facade to get the current panel and check the ID
        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return redirect()->to(AchatResource::getUrl('index'));
        }
 
        if (Filament::getCurrentPanel()->getId() === 'app') {
            return redirect()->to(AchatResource::getUrl('index'));
        }
 
        return parent::toResponse($request);
    }
}