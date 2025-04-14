<?php

namespace App\Filament\Resources\CreditBancaireResource\Pages;

use App\Filament\Resources\CreditBancaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditBancaires extends ListRecords
{
    protected static string $resource = CreditBancaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
