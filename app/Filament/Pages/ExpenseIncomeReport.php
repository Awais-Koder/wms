<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ExpenseIncomeStatsWidget;
use App\Models\UserExpense;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ExpenseIncomeReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.expense-income-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static ?string $navigationLabel = 'Expense & Income Report';

    protected static ?string $title = 'Expense & Income Report';

    protected static ?int $navigationSort = 6;

    public ?array $filters = [];

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseIncomeStatsWidget::class,
        ];
    }

    protected function getHeaderWidgetsData(): array
    {
        return $this->filters;
    }

    public function applyFilters(): void
    {
        // Filters are already bound via form statePath
    }

    public function resetFilters(): void
    {
        $this->filters = [];
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        $query = UserExpense::query()->with(['user']);

        // Apply page-level filters
        if ($this->filters['date_from'] ?? null) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if ($this->filters['date_to'] ?? null) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if ($this->filters['month'] ?? null) {
            $query->whereMonth('created_at', $this->filters['month']);
        }

        if ($this->filters['year'] ?? null) {
            $query->whereYear('created_at', $this->filters['year']);
        }

        return $table
            ->heading('User Expenses')
            ->query($query)
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('details')
                    ->label('Expense details')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Expense Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PKR')
                    ->sortable()
                    ->summarize(Sum::make()->money('PKR')->label('Total Expenses')),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Month')
                    ->date('F Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->relationship('user', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->filtersFormColumns(1)
            ->persistFiltersInSession()
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::Dropdown)
            ->defaultSort('created_at', 'desc')
            ->headerActions([]);
    }
}
