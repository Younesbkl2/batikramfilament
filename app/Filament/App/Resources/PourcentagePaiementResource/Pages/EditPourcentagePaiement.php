<?php

namespace App\Filament\App\Resources\PourcentagePaiementResource\Pages;

use App\Filament\App\Resources\PourcentagePaiementResource;
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
