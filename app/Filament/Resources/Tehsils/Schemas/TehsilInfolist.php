<?php

namespace App\Filament\Resources\Tehsils\Schemas;

use App\Models\Tehsil;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TehsilInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('district.name')
                    ->label('District'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Tehsil $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
