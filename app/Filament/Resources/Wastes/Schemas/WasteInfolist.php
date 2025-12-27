<?php

namespace App\Filament\Resources\Wastes\Schemas;

use App\Models\Waste;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WasteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hospital.name')
                    ->label('Hospital'),
                TextEntry::make('weight')
                    ->numeric(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Waste $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
