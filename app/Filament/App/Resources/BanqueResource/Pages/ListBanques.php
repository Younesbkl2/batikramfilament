<?php

namespace App\Filament\App\Resources\BanqueResource\Pages;

use App\Filament\App\Resources\BanqueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanques extends ListRecords
{
    protected static string $resource = BanqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
