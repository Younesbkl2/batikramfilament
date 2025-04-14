<?php

namespace App\Filament\App\Resources\ActFinalResource\Pages;

use App\Filament\App\Resources\ActFinalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActFinal extends EditRecord
{
    protected static string $resource = ActFinalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
