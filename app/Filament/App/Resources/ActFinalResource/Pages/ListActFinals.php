<?php

namespace App\Filament\App\Resources\ActFinalResource\Pages;

use App\Filament\App\Resources\ActFinalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActFinals extends ListRecords
{
    protected static string $resource = ActFinalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
