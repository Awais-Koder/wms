<?php

namespace App\Filament\Resources\HospitalPayments\Pages;

use App\Filament\Resources\HospitalPayments\HospitalPaymentResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateHospitalPayment extends CreateRecord
{
    protected static string $resource = HospitalPaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Calculate amounts based on payment type
        if ($data['payment_type'] === 'full') {
            $data['paid_amount'] = $data['amount'];
            $data['remaining_amount'] = 0;
        } else {
            $data['remaining_amount'] = $data['amount'] - ($data['paid_amount'] ?? 0);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        
        if ($record->is_collected && $record->collectedBy) {
            $admins = User::role('super_admin')->get();
            
            $paymentTypeText = $record->payment_type === 'full' ? 'Full' : 'Partial';
            $amountText = $record->payment_type === 'full' 
                ? "PKR {$record->paid_amount}" 
                : "PKR {$record->paid_amount} (Remaining: PKR {$record->remaining_amount})";
            
            foreach ($admins as $admin) {
                Notification::make()
                    ->title("{$paymentTypeText} Payment Collected")
                    ->body("{$amountText} for {$record->hospital->name} ({$record->month->format('F Y')}) has been collected by {$record->collectedBy->name}.")
                    ->success()
                    ->sendToDatabase($admin);
            }
        }
    }
}
