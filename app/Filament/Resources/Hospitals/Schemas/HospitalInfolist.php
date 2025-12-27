<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use App\Models\Hospital;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HospitalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('uuid')
                    ->label('Hospital Number'),
                TextEntry::make('country.name')
                    ->label('Country'),
                TextEntry::make('district.name')
                    ->label('District'),
                TextEntry::make('tehsil.name')
                    ->label('Tehsil'),
                TextEntry::make('city.name')
                    ->label('City'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('doctor_name'),
                TextEntry::make('cnic'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('agreement_duration')
                    ->numeric(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('phc_number'),
                TextEntry::make('mobile_number_1'),
                TextEntry::make('mobile_number_2'),
                ImageEntry::make('agreement_image'),
                TextEntry::make('province.name')
                    ->label('Province'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Hospital $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
