<?php

namespace App\Filament\Resources\HospitalPayments;

use App\Filament\Resources\HospitalPayments\Pages\CreateHospitalPayment;
use App\Filament\Resources\HospitalPayments\Pages\EditHospitalPayment;
use App\Filament\Resources\HospitalPayments\Pages\ListHospitalPayments;
use App\Filament\Resources\HospitalPayments\Pages\ViewHospitalPayment;
use App\Filament\Resources\HospitalPayments\Schemas\HospitalPaymentForm;
use App\Filament\Resources\HospitalPayments\Schemas\HospitalPaymentInfolist;
use App\Filament\Resources\HospitalPayments\Tables\HospitalPaymentsTable;
use App\Models\HospitalPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class HospitalPaymentResource extends Resource
{
    protected static ?string $model = HospitalPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string | UnitEnum | null $navigationGroup = 'Hospital Settings';

    public static function form(Schema $schema): Schema
    {
        return HospitalPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HospitalPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HospitalPaymentsTable::configure($table);
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
            'index' => ListHospitalPayments::route('/'),
            'create' => CreateHospitalPayment::route('/create'),
            'view' => ViewHospitalPayment::route('/{record}'),
            'edit' => EditHospitalPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if (!auth()->user()?->hasRole('super_admin')) {
            $query->whereHas('hospital', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return $query;
    }

    public static function getNavigationLabel(): string
    {
        return 'Hospital Payments';
    }

    public static function getModelLabel(): string
    {
        return 'Hospital Payment';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Hospital Payments';
    }
}
