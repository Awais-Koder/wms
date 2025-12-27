<?php

namespace App\Filament\Resources\HospitalPayments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HospitalPaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('hospital.name')
                            ->label('Hospital'),
                        TextEntry::make('month')
                            ->label('Month')
                            ->date('F Y'),
                        TextEntry::make('payment_type')
                            ->label('Payment Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'full' => 'success',
                                'partial' => 'warning',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    ])
                    ->columns(3),
                
                Section::make('Amount Details')
                    ->schema([
                        TextEntry::make('amount')
                            ->label('Total Amount')
                            ->money('PKR'),
                        TextEntry::make('paid_amount')
                            ->label('Paid Amount')
                            ->money('PKR'),
                        TextEntry::make('remaining_amount')
                            ->label('Remaining Amount')
                            ->money('PKR')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    ])
                    ->columns(3),
                
                Section::make('Collection Status')
                    ->schema([
                        IconEntry::make('is_collected')
                            ->label('Status')
                            ->boolean(),
                        TextEntry::make('collection_date')
                            ->label('Collection Date')
                            ->date()
                            ->placeholder('Not collected yet'),
                        TextEntry::make('collectedBy.name')
                            ->label('Collected By')
                            ->placeholder('Not collected yet'),
                    ])
                    ->columns(3),
                
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')
                            ->placeholder('No notes'),
                    ]),
            ]);
    }
}
