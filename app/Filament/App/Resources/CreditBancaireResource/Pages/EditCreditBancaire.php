<?php

namespace App\Filament\App\Resources\CreditBancaireResource\Pages;

use App\Filament\App\Resources\CreditBancaireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditBancaire extends EditRecord
{
    protected static string $resource = CreditBancaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
