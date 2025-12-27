<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HospitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Hospital identification and location details')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('uuid')
                            ->label('Hospital Number')
                            ->required(),
                        Textarea::make('address')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Location Details')
                    ->description('Geographic hierarchy for the hospital')
                    ->schema([
                        Select::make('country_id')
                            ->relationship('country', 'name')
                            ->required()
                            ->default(fn() => Country::where('name', 'Pakistan')->value('id')),
                        Select::make('province_id')
                            ->relationship('province', 'name')
                            ->required()
                            ->default(fn() => Province::where('name', 'Punjab')->value('id')),
                        Select::make('district_id')
                            ->relationship('district', 'name')
                            ->required()
                            ->default(fn() => District::where('name', 'Bahawalpur')->value('id')),
                        Select::make('tehsil_id')
                            ->relationship('tehsil', 'name')
                            ->required(),
                        Select::make('city_id')
                            ->relationship('city', 'name')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Doctor Information')
                    ->description('Details about the hospital doctor')
                    ->schema([
                        TextInput::make('doctor_name')
                            ->required(),
                        TextInput::make('cnic')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Agreement Details')
                    ->description('Contract and financial information')
                    ->schema([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('PKR'),
                        TextInput::make('agreement_duration')
                            ->required()
                            ->numeric()
                            ->suffix('months'),
                        DatePicker::make('date')
                            ->required(),
                        FileUpload::make('agreement_image')
                            ->image()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Contact Information')
                    ->description('Phone and communication details')
                    ->schema([
                        TextInput::make('phc_number')
                            ->required()
                            ->tel(),
                        TextInput::make('mobile_number_1')
                            ->required()
                            ->tel(),
                        TextInput::make('mobile_number_2')
                            ->required()
                            ->tel(),
                    ])
                    ->columns(3),

                Section::make('User Assignment')
                    ->description('Assign a user to manage this hospital')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(fn() => auth()->id())
                            ->visible(fn() => auth()->user()?->hasRole('super_admin')),
                    ])
                    ->hidden(fn() => !auth()->user()?->hasRole('super_admin')),
            ]);
    }
}
