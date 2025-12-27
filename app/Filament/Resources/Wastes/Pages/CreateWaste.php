<?php

namespace App\Filament\Resources\Wastes\Pages;

use App\Filament\Resources\Wastes\WasteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWaste extends CreateRecord
{
    protected static string $resource = WasteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
