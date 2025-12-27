<?php

namespace App\Filament\Exports;

use App\Models\UserExpense;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExpenseExporter extends Exporter
{
    protected static ?string $model = UserExpense::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name')
                ->label('User Name'),
            ExportColumn::make('details')
                ->label('Expense Details'),
            ExportColumn::make('amount')
                ->label('Amount (PKR)')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ExportColumn::make('created_at')
                ->label('Expense Date')
                ->formatStateUsing(fn ($state) => $state?->format('d M Y')),
            ExportColumn::make('created_at')
                ->label('Month')
                ->formatStateUsing(fn ($state) => $state?->format('F Y')),
            ExportColumn::make('updated_at')
                ->label('Last Updated')
                ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your expense & income report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
