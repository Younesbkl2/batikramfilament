<?php

namespace App\Filament\App\Resources\PaiementResource\Pages;

use App\Filament\App\Resources\PaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaiement extends EditRecord
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
