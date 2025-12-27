<?php

namespace App\Filament\Resources\UserSalaries\Schemas;

use App\Models\Hospital;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserSalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Employee')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $user = User::find($state);
                                    if ($user) {
                                        $set('base_salary', $user->base_salary);
                                        $set('commission_per_hospital', $user->commission_per_hospital);
                                        
                                        // Calculate hospitals count
                                        $month = $get('month');
                                        if ($month) {
                                            $hospitalsCount = Hospital::where('user_id', $state)->count();
                                            $set('hospitals_count', $hospitalsCount);
                                            $set('commission_amount', $user->commission_per_hospital * $hospitalsCount);
                                            
                                            // Calculate total
                                            $total = $user->base_salary + ($user->commission_per_hospital * $hospitalsCount) + ($get('bonus') ?? 0) - ($get('deductions') ?? 0);
                                            $set('total_salary', $total);
                                        }
                                    }
                                }
                            }),
                        DatePicker::make('month')
                            ->label('Salary Month')
                            ->required()
                            ->displayFormat('F Y')
                            ->format('Y-m-d')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $userId = $get('user_id');
                                if ($userId && $state) {
                                    $hospitalsCount = Hospital::where('user_id', $userId)->count();
                                    $set('hospitals_count', $hospitalsCount);
                                    
                                    $commissionPerHospital = $get('commission_per_hospital') ?? 0;
                                    $set('commission_amount', $commissionPerHospital * $hospitalsCount);
                                    
                                    // Calculate total
                                    $baseSalary = $get('base_salary') ?? 0;
                                    $bonus = $get('bonus') ?? 0;
                                    $deductions = $get('deductions') ?? 0;
                                    $total = $baseSalary + ($commissionPerHospital * $hospitalsCount) + $bonus - $deductions;
                                    $set('total_salary', $total);
                                }
                            })
                            ->rules([
                                fn (callable $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $userId = $get('user_id');
                                    if (!$userId || !$value) {
                                        return;
                                    }
                                    
                                    $exists = \App\Models\UserSalary::query()
                                        ->where('user_id', $userId)
                                        ->whereDate('month', $value)
                                        ->when($get('../../id'), fn ($query, $id) => $query->where('id', '!=', $id))
                                        ->exists();
                                    
                                    if ($exists) {
                                        $monthFormatted = \Carbon\Carbon::parse($value)->format('F Y');
                                        $fail("Salary record for {$monthFormatted} already exists for this employee.");
                                    }
                                },
                            ]),
                    ])
                    ->columns(2),

                Section::make('Salary Breakdown')
                    ->schema([
                        TextInput::make('base_salary')
                            ->label('Base Salary')
                            ->required()
                            ->numeric()
                            ->prefix('PKR')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-filled from employee profile'),
                        TextInput::make('commission_per_hospital')
                            ->label('Commission Per Hospital')
                            ->required()
                            ->numeric()
                            ->prefix('PKR')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-filled from employee profile'),
                        TextInput::make('hospitals_count')
                            ->label('Number of Hospitals Managed')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-calculated based on hospitals assigned'),
                        TextInput::make('commission_amount')
                            ->label('Total Commission')
                            ->required()
                            ->numeric()
                            ->prefix('PKR')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Commission per hospital × Hospitals count'),
                        TextInput::make('bonus')
                            ->label('Bonus')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $baseSalary = $get('base_salary') ?? 0;
                                $commissionAmount = $get('commission_amount') ?? 0;
                                $bonus = $state ?? 0;
                                $deductions = $get('deductions') ?? 0;
                                $set('total_salary', $baseSalary + $commissionAmount + $bonus - $deductions);
                            }),
                        TextInput::make('deductions')
                            ->label('Deductions')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $baseSalary = $get('base_salary') ?? 0;
                                $commissionAmount = $get('commission_amount') ?? 0;
                                $bonus = $get('bonus') ?? 0;
                                $deductions = $state ?? 0;
                                $set('total_salary', $baseSalary + $commissionAmount + $bonus - $deductions);
                            }),
                        TextInput::make('total_salary')
                            ->label('Total Salary')
                            ->required()
                            ->numeric()
                            ->prefix('PKR')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Base + Commission + Bonus - Deductions'),
                    ])
                    ->columns(3),

                Section::make('Payment Status')
                    ->schema([
                        Checkbox::make('is_paid')
                            ->label('Mark as Paid')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('paid_date', now()->toDateString());
                                } else {
                                    $set('paid_date', null);
                                }
                            }),
                        DatePicker::make('paid_date')
                            ->label('Payment Date')
                            ->visible(fn (callable $get) => $get('is_paid')),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
