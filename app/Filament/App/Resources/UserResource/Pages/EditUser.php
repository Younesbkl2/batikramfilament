<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['new_password'])) { 
            $data['password'] = Hash::make($data['new_password']); // Correctly hash & update password
        }
    
        unset($data['new_password'], $data['new_password_confirmation']); // Remove unused fields
    
        return $data;
    }

}
