<?php

namespace App\Filament\Resources\UserSalaries\Pages;

use App\Filament\Resources\UserSalaries\UserSalaryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserSalary extends ViewRecord
{
    protected static string $resource = UserSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
