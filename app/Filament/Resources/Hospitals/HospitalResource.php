<?php

namespace App\Filament\Resources\Hospitals;

use App\Filament\Resources\Hospitals\Pages\CreateHospital;
use App\Filament\Resources\Hospitals\Pages\EditHospital;
use App\Filament\Resources\Hospitals\Pages\ListHospitals;
use App\Filament\Resources\Hospitals\Pages\ViewHospital;
use App\Filament\Resources\Hospitals\Schemas\HospitalForm;
use App\Filament\Resources\Hospitals\Schemas\HospitalInfolist;
use App\Filament\Resources\Hospitals\Tables\HospitalsTable;
use App\Models\Hospital;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class HospitalResource extends Resource
{
    protected static ?string $model = Hospital::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static string | UnitEnum | null $navigationGroup = 'Hospital Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HospitalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HospitalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HospitalsTable::configure($table);
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
            'index' => ListHospitals::route('/'),
            'create' => CreateHospital::route('/create'),
            'view' => ViewHospital::route('/{record}'),
            'edit' => EditHospital::route('/{record}/edit'),
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
