<?php

namespace App\Filament\Resources\UserSalaries\Pages;

use App\Filament\Resources\UserSalaries\UserSalaryResource;
use App\Models\UserExpense;
use Filament\Resources\Pages\CreateRecord;

class CreateUserSalary extends CreateRecord
{
    protected static string $resource = UserSalaryResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        
        if ($record->is_paid) {
            $this->createExpenseRecord($record);
        }
    }

    protected function createExpenseRecord($salary): void
    {
        UserExpense::create([
            'user_id' => $salary->user_id,
            'type' => 'Salary Payment',
            'amount' => $salary->total_salary,
            'details' => "Salary payment for {$salary->month->format('F Y')} - Base: PKR {$salary->base_salary}, Commission: PKR {$salary->commission_amount}, Bonus: PKR {$salary->bonus}, Deductions: PKR {$salary->deductions}",
        ]);
    }
}
