<?php

namespace App\Filament\Resources\Tehsils\Pages;

use App\Filament\Resources\Tehsils\TehsilResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTehsil extends ViewRecord
{
    protected static string $resource = TehsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
