<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('cnic')
                            ->label('CNIC')
                            ->maxLength(15),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('User Roles'),
                    ])
                    ->columns(2),

                Section::make('Location Information')
                    ->schema([
                        Select::make('country_id')
                            ->label('Country')
                            ->relationship('country', 'name')
                            ->default(fn () => Country::where('name', 'Pakistan')->value('id'))
                            ->searchable()
                            ->preload(),
                        Select::make('province_id')
                            ->label('Province')
                            ->relationship('province', 'name')
                            ->default(fn () => Province::where('name', 'Punjab')->value('id'))
                            ->searchable()
                            ->preload(),
                        Select::make('district_id')
                            ->label('District')
                            ->relationship('district', 'name')
                            ->default(fn () => District::where('name', 'Bahawalpur')->value('id'))
                            ->searchable()
                            ->preload(),
                        Select::make('tehsil_id')
                            ->label('Tehsil')
                            ->relationship('tehsil', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('location')
                            ->label('Detailed Location')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Primary Phone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('alternate_phone')
                            ->label('Alternate Phone')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Salary Information')
                    ->schema([
                        TextInput::make('base_salary')
                            ->label('Base Salary')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Monthly base salary'),
                        TextInput::make('commission_per_hospital')
                            ->label('Commission Per Hospital')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Commission earned per hospital managed'),
                        TextInput::make('account_number')
                            ->label('Bank Account Number')
                            ->maxLength(50),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Security')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->helperText(fn (string $context): string => $context === 'edit' ? 'Leave blank to keep current password' : 'Must be at least 8 characters'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make('Profile Images')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Upload Images')
                            ->image()
                            ->multiple()
                            ->maxFiles(10)
                            ->reorderable()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
