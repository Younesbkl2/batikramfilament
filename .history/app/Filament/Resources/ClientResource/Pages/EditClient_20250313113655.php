<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Database\Eloquent\Builder;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure we can retrieve soft-deleted records
        return static::getModel()::withTrashed()->findOrFail($this->record->getKey())->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\RestoreAction::make(), // Allow restoring deleted clients
            Actions\DeleteAction::make(),
        ];
    }
}
