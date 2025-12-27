<?php

namespace App\Filament\Resources\HospitalPayments\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HospitalPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hospital.name')
                    ->label('Hospital')
                    ->searchable(['name', 'uuid'])
                    ->description(fn($record) => 'Hospital #: ' . $record->hospital?->uuid)
                    ->sortable(),
                TextColumn::make('month')
                    ->date('F Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'full' => 'success',
                        'partial' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                TextColumn::make('amount')
                    ->label('Total Amount')
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('remaining_amount')
                    ->label('Remaining')
                    ->money('PKR')
                    ->sortable()
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success'),
                IconColumn::make('is_collected')
                    ->label('Collected')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('collectedBy.name')
                    ->label('Collected By')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Not collected yet'),
                TextColumn::make('collection_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_collected')
                    ->label('Status')
                    ->options([
                        '0' => 'Pending',
                        '1' => 'Collected',
                    ]),
                TrashedFilter::make(),
            ])
            ->defaultSort('month', 'desc')
            ->recordActions([
                Action::make('downloadInvoice')
                    ->label('Download Invoice')
                    ->icon(Heroicon::DocumentArrowDown)
                    ->color('success')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.hospital-payment-invoice', ['payment' => $record]);
                        $filename = 'invoice-' . str_pad($record->id, 6, '0', STR_PAD_LEFT) . '-' . $record->month->format('Y-m') . '.pdf';

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $filename);
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
                    ->hidden(fn() => ! auth()->user()?->hasRole('super_admin')),
            ]);
    }
}
