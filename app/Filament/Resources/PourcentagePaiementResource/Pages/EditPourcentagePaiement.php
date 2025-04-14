<?php

namespace App\Filament\Resources\PourcentagePaiementResource\Pages;

use App\Filament\Resources\PourcentagePaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPourcentagePaiement extends EditRecord
{
    protected static string $resource = PourcentagePaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
