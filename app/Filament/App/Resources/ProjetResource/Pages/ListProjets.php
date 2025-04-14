<?php

namespace App\Filament\App\Resources\ProjetResource\Pages;

use App\Filament\App\Resources\ProjetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjets extends ListRecords
{
    protected static string $resource = ProjetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
