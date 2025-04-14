<?php

namespace App\Filament\Resources\AttestationResource\Pages;

use App\Filament\Resources\AttestationResource;
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
