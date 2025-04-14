<?php

namespace App\Filament\App\Resources\AchatResource\Pages;

use App\Filament\App\Resources\AchatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAchat extends EditRecord
{
    protected static string $resource = AchatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
