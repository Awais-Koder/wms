<?php

namespace App\Filament\Resources\HospitalPayments\Pages;

use App\Filament\Resources\HospitalPayments\HospitalPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHospitalPayments extends ListRecords
{
    protected static string $resource = HospitalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
