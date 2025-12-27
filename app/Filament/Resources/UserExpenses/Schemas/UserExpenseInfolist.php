<?php

namespace App\Filament\Resources\UserExpenses\Schemas;

use App\Models\UserExpense;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('details')
                    ->columnSpanFull(),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (UserExpense $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
