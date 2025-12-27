<?php

namespace App\Filament\Resources\UserSalaries;

use App\Filament\Resources\UserSalaries\Pages\CreateUserSalary;
use App\Filament\Resources\UserSalaries\Pages\EditUserSalary;
use App\Filament\Resources\UserSalaries\Pages\ListUserSalaries;
use App\Filament\Resources\UserSalaries\Schemas\UserSalaryForm;
use App\Filament\Resources\UserSalaries\Tables\UserSalariesTable;
use App\Models\UserSalary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSalaryResource extends Resource
{
    protected static ?string $model = UserSalary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    
    protected static ?string $navigationLabel = 'Salaries';
    
    protected static ?string $modelLabel = 'Salary';
    
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return UserSalaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserSalariesTable::configure($table);
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
            'index' => ListUserSalaries::route('/'),
            'create' => CreateUserSalary::route('/create'),
            'view' => Pages\ViewUserSalary::route('/{record}'),
            'edit' => EditUserSalary::route('/{record}/edit'),
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
