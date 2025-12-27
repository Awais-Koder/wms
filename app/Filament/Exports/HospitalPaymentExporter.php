<?php

namespace App\Filament\Exports;

use App\Models\HospitalPayment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class HospitalPaymentExporter extends Exporter
{
    protected static ?string $model = HospitalPayment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Payment ID'),
            ExportColumn::make('hospital.name')
                ->label('Hospital Name'),
            ExportColumn::make('hospital.user.name')
                ->label('Assigned User'),
            ExportColumn::make('collectedBy.name')
                ->label('Collected By'),
            ExportColumn::make('paid_amount')
                ->label('Paid Amount (PKR)')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ExportColumn::make('remaining_amount')
                ->label('Remaining Amount (PKR)')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ExportColumn::make('collection_date')
                ->label('Collection Date')
                ->formatStateUsing(fn ($state) => $state?->format('d M Y')),
            ExportColumn::make('is_collected')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state ? 'Collected' : 'Pending'),
            ExportColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i')),
        ];
    }

    public static function modifyQuery(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_collected', true);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your hospital payment (income) report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
