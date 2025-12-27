<?php

namespace App\Filament\Resources\HospitalPayments\Pages;

use App\Filament\Resources\HospitalPayments\HospitalPaymentResource;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action as PageAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditHospitalPayment extends EditRecord
{
    protected static string $resource = HospitalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PageAction::make('downloadInvoice')
                ->label('Download Invoice')
                ->icon(Heroicon::DocumentArrowDown)
                ->color('success')
                ->action(function () {
                    $payment = $this->getRecord();
                    $pdf = Pdf::loadView('pdf.hospital-payment-invoice', ['payment' => $payment]);
                    
                    $filename = 'invoice-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '-' . $payment->month->format('Y-m') . '.pdf';
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                }),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        
        if ($record->is_collected && $record->wasChanged('is_collected') && $record->collectedBy) {
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
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->url(HospitalPaymentResource::getUrl('edit', ['record' => $record])),
                    ])
                    ->sendToDatabase($admin);
            }
        }
    }
}
