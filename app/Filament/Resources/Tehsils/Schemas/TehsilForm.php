<?php

namespace App\Filament\Resources\Tehsils\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TehsilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('district_id')
                    ->relationship('district', 'name')
                    ->required(),
            ]);
    }
}
