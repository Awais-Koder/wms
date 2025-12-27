<?php

namespace App\Filament\Resources\UserExpenses\Pages;

use App\Filament\Resources\UserExpenses\UserExpenseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserExpense extends ViewRecord
{
    protected static string $resource = UserExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
