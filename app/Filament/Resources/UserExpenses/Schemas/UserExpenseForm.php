<?php

namespace App\Filament\Resources\UserExpenses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expense Information')
                    ->schema([
                        TextInput::make('type')
                            ->label('Expense Type')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Transportation, Meals, Office Supplies'),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->required()
                            ->numeric()
                            ->prefix('PKR')
                            ->minValue(0)
                            ->step(0.01),
                        Textarea::make('details')
                            ->label('Details')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('User Assignment')
                    ->schema([
                        Select::make('user_id')
                            ->label('Assigned User')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->visible(fn () => auth()->user()?->hasRole('super_admin')),
                    ])
                    ->hidden(fn () => !auth()->user()?->hasRole('super_admin')),
            ]);
    }
}
