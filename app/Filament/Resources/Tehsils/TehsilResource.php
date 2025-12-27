<?php

namespace App\Filament\Resources\Tehsils;

use App\Filament\Resources\Tehsils\Pages\CreateTehsil;
use App\Filament\Resources\Tehsils\Pages\EditTehsil;
use App\Filament\Resources\Tehsils\Pages\ListTehsils;
use App\Filament\Resources\Tehsils\Pages\ViewTehsil;
use App\Filament\Resources\Tehsils\Schemas\TehsilForm;
use App\Filament\Resources\Tehsils\Schemas\TehsilInfolist;
use App\Filament\Resources\Tehsils\Tables\TehsilsTable;
use App\Models\Tehsil;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TehsilResource extends Resource
{
    protected static ?string $model = Tehsil::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TehsilForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TehsilInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TehsilsTable::configure($table);
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
            'index' => ListTehsils::route('/'),
            'create' => CreateTehsil::route('/create'),
            'view' => ViewTehsil::route('/{record}'),
            'edit' => EditTehsil::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
