<?php

namespace App\Filament\App\Resources\AchatResource\Pages;

use App\Filament\App\Resources\AchatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAchats extends ListRecords
{
    protected static string $resource = AchatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
