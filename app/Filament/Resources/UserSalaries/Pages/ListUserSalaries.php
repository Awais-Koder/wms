<?php

namespace App\Filament\Resources\UserSalaries\Pages;

use App\Filament\Resources\UserSalaries\UserSalaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserSalaries extends ListRecords
{
    protected static string $resource = UserSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
