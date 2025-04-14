<?php

namespace App\Filament\App\Resources\LocalResource\Pages;

use App\Filament\App\Resources\LocalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocal extends EditRecord
{
    protected static string $resource = LocalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
