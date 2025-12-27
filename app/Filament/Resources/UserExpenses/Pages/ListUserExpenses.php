<?php

namespace App\Filament\Resources\UserExpenses\Pages;

use App\Filament\Resources\UserExpenses\UserExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserExpenses extends ListRecords
{
    protected static string $resource = UserExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
