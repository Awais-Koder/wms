<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\City;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('tehsil.name')
                    ->label('Tehsil'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (City $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
