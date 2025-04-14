<?php

namespace App\Filament\Resources\EtatPaiementResource\Pages;

use App\Filament\Resources\EtatPaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEtatPaiement extends EditRecord
{
    protected static string $resource = EtatPaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
