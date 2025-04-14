<?php

namespace App\Filament\Resources\PourcentagePaiementResource\Pages;

use App\Filament\Resources\PourcentagePaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPourcentagePaiements extends ListRecords
{
    protected static string $resource = PourcentagePaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
