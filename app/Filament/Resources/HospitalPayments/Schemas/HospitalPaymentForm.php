<?php

namespace App\Filament\Resources\HospitalPayments\Schemas;

use App\Models\Hospital;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HospitalPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hospital_id')
                    ->relationship(
                        'hospital',
                        'name',
                        fn ($query) => auth()->user()?->hasRole('super_admin')
                            ? $query
                            : $query->where('user_id', auth()->id())
                    )
                    ->required()
                    ->searchable(['name', 'uuid'])
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' ('.$record->uuid.')')
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $hospital = Hospital::find($state);
                            if ($hospital) {
                                $set('amount', $hospital->amount);
                            }
                        }
                    }),
                DatePicker::make('month')
                    ->label('Payment Month')
                    ->required()
                    ->displayFormat('F Y')
                    ->format('Y-m-d')
                    ->helperText('Select the payment month')
                    ->reactive()
                    ->rules([
                        fn (callable $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $hospitalId = $get('hospital_id');
                            if (! $hospitalId || ! $value) {
                                return;
                            }

                            $exists = \App\Models\HospitalPayment::query()
                                ->where('hospital_id', $hospitalId)
                                ->whereDate('month', $value)
                                ->when($get('../../id'), fn ($query, $id) => $query->where('id', '!=', $id))
                                ->exists();

                            if ($exists) {
                                $monthFormatted = \Carbon\Carbon::parse($value)->format('F Y');
                                $fail("Payment record for {$monthFormatted} already exists for this hospital.");
                            }
                        },
                    ]),
                Select::make('payment_type')
                    ->label('Payment Type')
                    ->options([
                        'full' => 'Full Payment',
                        'partial' => 'Partial Payment',
                    ])
                    ->default('full')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state === 'full') {
                            $set('paid_amount', $get('amount'));
                            $set('remaining_amount', 0);
                        } else {
                            $set('paid_amount', null);
                        }
                    }),
                TextInput::make('amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->prefix('PKR')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Auto-filled from hospital agreement'),
                TextInput::make('paid_amount')
                    ->label('Paid Amount')
                    ->numeric()
                    ->prefix('PKR')
                    ->required()
                    ->visible(fn (callable $get) => $get('payment_type') === 'partial')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $totalAmount = $get('amount') ?? 0;
                        $paidAmount = $state ?? 0;
                        $set('remaining_amount', max(0, $totalAmount - $paidAmount));
                    })
                    ->rules([
                        fn (callable $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $totalAmount = $get('amount');
                            if ($value > $totalAmount) {
                                $fail("Paid amount cannot exceed total amount of PKR {$totalAmount}.");
                            }
                        },
                    ]),
                TextInput::make('remaining_amount')
                    ->label('Remaining Amount')
                    ->numeric()
                    ->prefix('PKR')
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn (callable $get) => $get('payment_type') === 'partial'),
                Checkbox::make('is_collected')
                    ->label('Payment Collected')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $set('collection_date', now()->toDateString());
                            $set('collected_by_user_id', auth()->id());

                            // Auto-set paid_amount for full payment
                            if ($get('payment_type') === 'full') {
                                $set('paid_amount', $get('amount'));
                                $set('remaining_amount', 0);
                            }
                        } else {
                            $set('collection_date', null);
                            $set('collected_by_user_id', null);
                        }
                    }),
                DatePicker::make('collection_date')
                    ->label('Collection Date')
                    ->visible(fn (callable $get) => $get('is_collected')),
                Select::make('collected_by_user_id')
                    ->relationship('collectedBy', 'name')
                    ->label('Collected By')
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn (callable $get) => $get('is_collected')),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
