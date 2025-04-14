<?php

namespace App\Filament\App\Resources\EtatPaiementCreditResource\Pages;

use App\Filament\App\Resources\EtatPaiementCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEtatPaiementCredits extends ListRecords
{
    protected static string $resource = EtatPaiementCreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
