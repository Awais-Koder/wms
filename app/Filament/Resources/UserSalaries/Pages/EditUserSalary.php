<?php

namespace App\Filament\Resources\UserSalaries\Pages;

use App\Filament\Resources\UserSalaries\UserSalaryResource;
use App\Models\UserExpense;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUserSalary extends EditRecord
{
    protected static string $resource = UserSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        
        // Only create expense if salary was just marked as paid
        if ($record->is_paid && $record->wasChanged('is_paid')) {
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
