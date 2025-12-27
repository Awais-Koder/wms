<?php

namespace App\Filament\Resources\UserExpenses;

use App\Filament\Resources\UserExpenses\Pages\CreateUserExpense;
use App\Filament\Resources\UserExpenses\Pages\EditUserExpense;
use App\Filament\Resources\UserExpenses\Pages\ListUserExpenses;
use App\Filament\Resources\UserExpenses\Pages\ViewUserExpense;
use App\Filament\Resources\UserExpenses\Schemas\UserExpenseForm;
use App\Filament\Resources\UserExpenses\Schemas\UserExpenseInfolist;
use App\Filament\Resources\UserExpenses\Tables\UserExpensesTable;
use App\Models\UserExpense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserExpenseResource extends Resource
{
    protected static ?string $model = UserExpense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return UserExpenseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserExpenseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserExpensesTable::configure($table);
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
            'index' => ListUserExpenses::route('/'),
            'create' => CreateUserExpense::route('/create'),
            'view' => ViewUserExpense::route('/{record}'),
            'edit' => EditUserExpense::route('/{record}/edit'),
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
