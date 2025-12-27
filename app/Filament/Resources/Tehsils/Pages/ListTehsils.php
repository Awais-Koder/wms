<?php

namespace App\Filament\Resources\Tehsils\Pages;

use App\Filament\Resources\Tehsils\TehsilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTehsils extends ListRecords
{
    protected static string $resource = TehsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
