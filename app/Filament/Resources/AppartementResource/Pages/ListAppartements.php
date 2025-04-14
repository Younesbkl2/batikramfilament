<?php

namespace App\Filament\Resources\AppartementResource\Pages;

use App\Filament\Resources\AppartementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppartements extends ListRecords
{
    protected static string $resource = AppartementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
