<?php

namespace App\Filament\Resources\Wastes;

use App\Filament\Resources\Wastes\Pages\CreateWaste;
use App\Filament\Resources\Wastes\Pages\EditWaste;
use App\Filament\Resources\Wastes\Pages\ListWastes;
use App\Filament\Resources\Wastes\Pages\ViewWaste;
use App\Filament\Resources\Wastes\Schemas\WasteForm;
use App\Filament\Resources\Wastes\Schemas\WasteInfolist;
use App\Filament\Resources\Wastes\Tables\WastesTable;
use App\Models\Waste;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WasteResource extends Resource
{
    protected static ?string $model = Waste::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Trash;

    public static function form(Schema $schema): Schema
    {
        return WasteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WasteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WastesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWastes::route('/'),
            'create' => CreateWaste::route('/create'),
            'view' => ViewWaste::route('/{record}'),
            'edit' => EditWaste::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if (!auth()->user()?->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
