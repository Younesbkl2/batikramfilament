<?php

namespace App\Filament\Resources\AchatResource\Pages;

use App\Filament\Resources\AchatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditAchat extends EditRecord
{
    protected static string $resource = AchatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(), // Allow restoring the record
        ];
    }

    // Override the resolveRecord method to include soft-deleted records
    protected function resolveRecord($key): Builder
    {
        return parent::resolveRecord($key)->withTrashed();
    }
}
