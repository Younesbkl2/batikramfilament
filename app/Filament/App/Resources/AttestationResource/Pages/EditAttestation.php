<?php

namespace App\Filament\App\Resources\AttestationResource\Pages;

use App\Filament\App\Resources\AttestationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttestation extends EditRecord
{
    protected static string $resource = AttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
