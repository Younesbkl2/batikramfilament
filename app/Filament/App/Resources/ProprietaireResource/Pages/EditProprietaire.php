<?php

namespace App\Filament\App\Resources\ProprietaireResource\Pages;

use App\Filament\App\Resources\ProprietaireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProprietaire extends EditRecord
{
    protected static string $resource = ProprietaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
