<?php

namespace App\Filament\Widgets;

use App\Models\Hospital;
use App\Models\HospitalPayment;
use App\Models\UserExpense;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpenseIncomeStatsWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $totalHospitalPaymentsCount = Hospital::sum('amount');
        $incomeStats = $this->getIncomeStats();
        $expenseStats = $this->getExpenseStats();
        $netProfit = $incomeStats['total_collected'] - $expenseStats['total_expenses'];

        return [
            Stat::make('Total Amount (All Hospitals)', 'PKR ' . number_format($totalHospitalPaymentsCount, 2))
                ->description('Total payments from all hospitals')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),
            Stat::make('Total Collected', 'PKR ' . number_format($incomeStats['total_collected'], 2))
                ->description($incomeStats['payments_count'] . ' payments collected')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Pending Amount', 'PKR ' . number_format($incomeStats['total_pending'], 2))
                ->description('Yet to be collected')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Total Expenses', 'PKR ' . number_format($expenseStats['total_expenses'], 2))
                ->description($expenseStats['expenses_count'] . ' expense records')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Net Profit/Loss', 'PKR ' . number_format($netProfit, 2))
                ->description($netProfit >= 0 ? 'Profit' : 'Loss')
                ->descriptionIcon($netProfit >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
        ];
    }

    protected function getIncomeStats(): array
    {
        $query = HospitalPayment::query();

        // Apply date filters
        if ($dateFrom = $this->filters['date_from'] ?? null) {
            $query->whereDate('collection_date', '>=', $dateFrom);
        }

        if ($dateTo = $this->filters['date_to'] ?? null) {
            $query->whereDate('collection_date', '<=', $dateTo);
        }

        if ($month = $this->filters['month'] ?? null) {
            $query->whereMonth('month', $month);
        }

        if ($year = $this->filters['year'] ?? null) {
            $query->whereYear('month', $year);
        }

        $totalAmountAllHospitals = (clone $query)->sum('amount');
        $totalPaymentsCount = (clone $query)->count();
        $totalIncome = (clone $query)->sum('amount');
        $totalCollected = (clone $query)->where('is_collected', true)->sum('paid_amount');
        $totalPending = $totalIncome - $totalCollected;
        $paymentsCount = (clone $query)->where('is_collected', true)->count();

        return [
            'total_amount_all_hospitals' => $totalAmountAllHospitals,
            'total_payments_count' => $totalPaymentsCount,
            'total_income' => $totalIncome,
            'total_collected' => $totalCollected,
            'total_pending' => $totalPending,
            'payments_count' => $paymentsCount,
        ];
    }

    protected function getExpenseStats(): array
    {
        $query = UserExpense::query();

        // Apply date filters
        if ($dateFrom = $this->filters['date_from'] ?? null) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $this->filters['date_to'] ?? null) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($month = $this->filters['month'] ?? null) {
            $query->whereMonth('created_at', $month);
        }

        if ($year = $this->filters['year'] ?? null) {
            $query->whereYear('created_at', $year);
        }

        return [
            'total_expenses' => $query->sum('amount'),
            'expenses_count' => $query->count(),
        ];
    }
    public static function isDiscovered(): bool
    {
        return false;
    }
}
