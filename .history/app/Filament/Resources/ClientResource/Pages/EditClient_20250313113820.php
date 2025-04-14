<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function resolveRecord($key): Model
    {
        // Retrieve soft-deleted records as well
        return static::getModel()::withTrashed()->whereKey($key)->firstOrFail();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\RestoreAction::make(), // Allow restoring deleted clients
            Actions\DeleteAction::make(),
        ];
    }
}
