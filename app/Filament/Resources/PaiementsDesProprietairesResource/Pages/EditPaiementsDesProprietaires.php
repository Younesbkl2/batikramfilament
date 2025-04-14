<?php

namespace App\Filament\Resources\PaiementsDesProprietairesResource\Pages;

use App\Filament\Resources\PaiementsDesProprietairesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaiementsDesProprietaires extends EditRecord
{
    protected static string $resource = PaiementsDesProprietairesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
