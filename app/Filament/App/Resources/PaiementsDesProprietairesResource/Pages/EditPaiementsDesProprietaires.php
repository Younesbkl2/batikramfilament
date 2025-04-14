<?php

namespace App\Filament\App\Resources\PaiementsDesProprietairesResource\Pages;

use App\Filament\App\Resources\PaiementsDesProprietairesResource;
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
