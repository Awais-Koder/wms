<?php

namespace App\Exports;

use App\Models\HospitalPayment;
use App\Models\UserExpense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseIncomeReportExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $expenseQuery = UserExpense::query()
            ->with(['user'])
            ->when($this->filters['user_id'] ?? null, fn ($query, $userId) => 
                $query->where('user_id', $userId)
            )
            ->when($this->filters['date_from'] ?? null, fn ($query, $date) => 
                $query->whereDate('created_at', '>=', $date)
            )
            ->when($this->filters['date_to'] ?? null, fn ($query, $date) => 
                $query->whereDate('created_at', '<=', $date)
            )
            ->when($this->filters['month'] ?? null, fn ($query, $month) => 
                $query->whereMonth('created_at', $month)
            )
            ->orderBy('created_at', 'desc');

        $incomeQuery = HospitalPayment::query()
            ->with(['hospital', 'collectedBy'])
            ->when($this->filters['user_id'] ?? null, function ($q, $userId) {
                $q->whereHas('hospital', fn ($query) => $query->where('user_id', $userId));
            })
            ->when($this->filters['date_from'] ?? null, fn ($q, $date) => 
                $q->whereDate('collection_date', '>=', $date)
            )
            ->when($this->filters['date_to'] ?? null, fn ($q, $date) => 
                $q->whereDate('collection_date', '<=', $date)
            )
            ->when($this->filters['month'] ?? null, fn ($q, $month) => 
                $q->whereMonth('collection_date', $month)
            )
            ->where('is_collected', true)
            ->orderBy('collection_date', 'desc');

        $expenses = $expenseQuery->get();
        $incomes = $incomeQuery->get();

        $incomeStats = [
            'total_collected' => $incomes->sum('paid_amount'),
            'total_remaining' => $incomes->sum('remaining_amount'),
            'payments_count' => $incomes->count(),
        ];

        $expenseStats = [
            'total_expenses' => $expenses->sum('amount'),
            'expenses_count' => $expenses->count(),
        ];

        return view('exports.expense-income-report', [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'incomeStats' => $incomeStats,
            'expenseStats' => $expenseStats,
            'filters' => $this->filters,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Expense & Income Report';
    }
}
