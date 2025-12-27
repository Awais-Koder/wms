<?php

namespace App\Filament\Resources\Hospitals\Pages;

use App\Filament\Resources\Hospitals\HospitalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHospital extends CreateRecord
{
    protected static string $resource = HospitalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
