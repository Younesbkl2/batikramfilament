<?php

namespace App\Filament\App\Resources\AttestationResource\Pages;

use App\Filament\App\Resources\AttestationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttestations extends ListRecords
{
    protected static string $resource = AttestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
