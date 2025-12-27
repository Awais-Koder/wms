<?php

namespace App\Filament\Resources\Wastes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WasteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Waste Collection Details')
                    ->schema([
                        Select::make('hospital_id')
                            ->label('Hospital')
                            ->relationship('hospital', 'name')
                            ->required()
                            ->searchable(['name', 'uuid'])
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' ('.$record->uuid.')')
                            ->preload(),
                        TextInput::make('weight')
                            ->label('Weight')
                            ->required()
                            ->numeric()
                            ->suffix('kg')
                            ->minValue(0)
                            ->step(0.01),
                        DatePicker::make('date')
                            ->label('Collection Date')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),
                    ])
                    ->columns(3),

                Section::make('User Assignment')
                    ->schema([
                        Select::make('user_id')
                            ->label('Collected By')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->visible(fn () => auth()->user()?->hasRole('super_admin')),
                    ])
                    ->hidden(fn () => ! auth()->user()?->hasRole('super_admin')),
            ]);
    }
}
