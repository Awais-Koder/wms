<?php

namespace App\Filament\Resources\Wastes\Pages;

use App\Filament\Resources\Wastes\WasteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWaste extends ViewRecord
{
    protected static string $resource = WasteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
