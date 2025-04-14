<?php

namespace App\Filament\Resources\ActFinalResource\Pages;

use App\Filament\Resources\ActFinalResource;
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
