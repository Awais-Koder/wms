<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 18px; font-weight: bold; padding: 10px;">
                Expense & Income Report
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 12px; padding: 5px;">
                Generated on: {{ now()->format('d M Y, h:i A') }}
            </th>
        </tr>
        @if(!empty($filters))
        <tr>
            <th colspan="6" style="text-align: center; font-size: 11px; padding: 5px;">
                Filters Applied: 
                @if($filters['user_id'] ?? null) User ID: {{ $filters['user_id'] }} | @endif
                @if($filters['date_from'] ?? null) From: {{ $filters['date_from'] }} | @endif
                @if($filters['date_to'] ?? null) To: {{ $filters['date_to'] }} | @endif
                @if($filters['month'] ?? null) Month: {{ $filters['month'] }} @endif
            </th>
        </tr>
        @endif
        <tr><th colspan="6"></th></tr>
        
        <!-- Summary Section -->
        <tr>
            <th colspan="6" style="background-color: #f3f4f6; font-weight: bold; padding: 8px;">
                SUMMARY
            </th>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #d1fae5; padding: 8px;">Income Summary</th>
            <th colspan="3" style="background-color: #fee2e2; padding: 8px;">Expense Summary</th>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;"><strong>Total Collected:</strong></td>
            <td colspan="3" style="padding: 5px;"><strong>Total Expenses:</strong></td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;">PKR {{ number_format($incomeStats['total_collected'], 2) }}</td>
            <td colspan="3" style="padding: 5px;">PKR {{ number_format($expenseStats['total_expenses'], 2) }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;"><strong>Total Remaining:</strong></td>
            <td colspan="3" style="padding: 5px;"><strong>Expense Count:</strong></td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;">PKR {{ number_format($incomeStats['total_remaining'], 2) }}</td>
            <td colspan="3" style="padding: 5px;">{{ number_format($expenseStats['expenses_count']) }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;"><strong>Payments Count:</strong></td>
            <td colspan="3" style="padding: 5px;"><strong>Net Profit/Loss:</strong></td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;">{{ number_format($incomeStats['payments_count']) }}</td>
            <td colspan="3" style="padding: 5px; font-weight: bold; color: {{ ($incomeStats['total_collected'] - $expenseStats['total_expenses']) >= 0 ? 'green' : 'red' }};">
                PKR {{ number_format($incomeStats['total_collected'] - $expenseStats['total_expenses'], 2) }}
            </td>
        </tr>
        <tr><th colspan="6"></th></tr>
        
        <!-- Income Details -->
        <tr>
            <th colspan="6" style="background-color: #d1fae5; font-weight: bold; padding: 8px;">
                INCOME DETAILS
            </th>
        </tr>
        <tr style="background-color: #f9fafb; font-weight: bold;">
            <th style="padding: 8px; border: 1px solid #ddd;">Hospital</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Month</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Payment Type</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Collected Amount</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Remaining</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Collected By</th>
        </tr>
    </thead>
    <tbody>
        @forelse($incomes as $income)
        <tr>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $income->hospital->name }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $income->month->format('F Y') }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ ucfirst($income->payment_type) }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">PKR {{ number_format($income->paid_amount, 2) }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">PKR {{ number_format($income->remaining_amount, 2) }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $income->collectedBy->name ?? 'N/A' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="padding: 8px; text-align: center; font-style: italic;">No income records found</td>
        </tr>
        @endforelse
        
        <!-- Empty Row -->
        <tr><th colspan="6"></th></tr>
        
        <!-- Expense Details -->
        <tr>
            <th colspan="6" style="background-color: #fee2e2; font-weight: bold; padding: 8px;">
                EXPENSE DETAILS
            </th>
        </tr>
        <tr style="background-color: #f9fafb; font-weight: bold;">
            <th style="padding: 8px; border: 1px solid #ddd;">User</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Type</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Amount</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Details</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Date</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Month</th>
        </tr>
        @forelse($expenses as $expense)
        <tr>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $expense->user->name }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $expense->type }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">PKR {{ number_format($expense->amount, 2) }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $expense->details }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $expense->created_at->format('d M Y') }}</td>
            <td style="padding: 6px; border: 1px solid #ddd;">{{ $expense->created_at->format('F Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="padding: 8px; text-align: center; font-style: italic;">No expense records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
