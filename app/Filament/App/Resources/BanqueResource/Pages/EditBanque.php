<?php

namespace App\Filament\App\Resources\BanqueResource\Pages;

use App\Filament\App\Resources\BanqueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBanque extends EditRecord
{
    protected static string $resource = BanqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
