<?php

namespace App\Filament\App\Resources\EtatPaiementResource\Pages;

use App\Filament\App\Resources\EtatPaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEtatPaiements extends ListRecords
{
    protected static string $resource = EtatPaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
