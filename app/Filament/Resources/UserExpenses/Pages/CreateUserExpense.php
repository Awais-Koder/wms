<?php

namespace App\Filament\Resources\UserExpenses\Pages;

use App\Filament\Resources\UserExpenses\UserExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserExpense extends CreateRecord
{
    protected static string $resource = UserExpenseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
