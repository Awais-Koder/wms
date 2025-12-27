<?php

namespace App\Filament\Resources\HospitalPayments\Pages;

use App\Filament\Resources\HospitalPayments\HospitalPaymentResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewHospitalPayment extends ViewRecord
{
    protected static string $resource = HospitalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadInvoice')
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
            EditAction::make(),
        ];
    }
}
