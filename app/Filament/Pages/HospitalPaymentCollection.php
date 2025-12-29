<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HospitalPaymentCollection extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.hospital-payment-collection';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?string $navigationLabel = 'Payment Collections';

    protected static ?string $title = 'Hospital Payment Collections';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }

    protected function getBaseQuery(): Builder
    {
        $tableFilters = $this->getTableFilters();

        // Build subquery for payment calculations with filters
        $paymentQuery = DB::table('hospital_payments')
            ->select('hospital_id')
            ->selectRaw('SUM(CASE WHEN is_collected = 1 THEN paid_amount ELSE 0 END) as collected_amount')
            ->selectRaw('SUM(amount) - SUM(CASE WHEN is_collected = 1 THEN paid_amount ELSE 0 END) as pending_amount')
            ->selectRaw('SUM(amount) as payment_total_amount')
            ->whereNull('deleted_at');

        // Apply filters to payment subquery
        if (isset($tableFilters['month']['month']) && $tableFilters['month']['month']) {
            $paymentQuery->whereMonth('month', $tableFilters['month']['month']);
        }

        if (isset($tableFilters['year']['year']) && $tableFilters['year']['year']) {
            $paymentQuery->whereYear('month', $tableFilters['year']['year']);
        }

        if (isset($tableFilters['date_range']['date_from']) && $tableFilters['date_range']['date_from']) {
            $paymentQuery->whereDate('collection_date', '>=', $tableFilters['date_range']['date_from']);
        }

        if (isset($tableFilters['date_range']['date_to']) && $tableFilters['date_range']['date_to']) {
            $paymentQuery->whereDate('collection_date', '<=', $tableFilters['date_range']['date_to']);
        }

        $paymentQuery->groupBy('hospital_id');

        // Main query with joined payment calculations
        return User::query()
            ->select([
                'users.*',
                DB::raw('COALESCE(SUM(payment_stats.collected_amount), 0) as collected_amount'),
                DB::raw('COALESCE(SUM(payment_stats.payment_total_amount), 0) - COALESCE(SUM(payment_stats.collected_amount), 0) as pending_amount'),
                DB::raw('COALESCE(SUM(hospitals.amount), 0) as total_amount'),
            ])
            ->leftJoin('hospitals', function ($join) {
                $join->on('users.id', '=', 'hospitals.user_id')
                    ->whereNull('hospitals.deleted_at');
            })
            ->leftJoinSub($paymentQuery, 'payment_stats', function ($join) {
                $join->on('hospitals.id', '=', 'payment_stats.hospital_id');
            })
            ->groupBy(
                'users.id',
                'users.country_id',
                'users.province_id',
                'users.district_id',
                'users.tehsil_id',
                'users.email',
                'users.base_salary',
                'users.commission_per_hospital',
                'users.name',
                'users.cnic',
                'users.location',
                'users.phone',
                'users.alternate_phone',
                'users.account_number',
                'users.salary',
                'users.commission',
                'users.images',
                'users.email_verified_at',
                'users.password',
                'users.remember_token',
                'users.deleted_at',
                'users.created_at',
                'users.updated_at'
            );
    }

    protected function applyFiltersToQuery(Builder|HasMany $query, User $record): Builder|HasMany
    {
        $tableFilters = $this->getTableFilters();

        $query->whereNull('hospital_payments.deleted_at');

        if (isset($tableFilters['month']['month']) && $tableFilters['month']['month']) {
            $query->whereMonth('hospital_payments.month', $tableFilters['month']['month']);
        }

        if (isset($tableFilters['year']['year']) && $tableFilters['year']['year']) {
            $query->whereYear('hospital_payments.month', $tableFilters['year']['year']);
        }

        if (isset($tableFilters['date_range']['date_from']) && $tableFilters['date_range']['date_from']) {
            $query->whereDate('hospital_payments.collection_date', '>=', $tableFilters['date_range']['date_from']);
        }

        if (isset($tableFilters['date_range']['date_to']) && $tableFilters['date_range']['date_to']) {
            $query->whereDate('hospital_payments.collection_date', '<=', $tableFilters['date_range']['date_to']);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('User Payment Collections')
            ->query($this->getBaseQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hospitals.uuid')
                    ->label('Hospital UUID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(30),
                TextColumn::make('hospitals_count')
                    ->label('Total Hospitals')
                    ->counts('hospitals')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('collected_amount')
                    ->label('Collected Payments')
                    ->money('PKR')
                    ->sortable()
                    ->summarize(Sum::make()->money('PKR')->label('Total Collected')),
                TextColumn::make('pending_amount')
                    ->label('Pending Payments')
                    ->money('PKR')
                    ->sortable()
                    ->summarize(Sum::make()->money('PKR')->label('Total Pending')),
                TextColumn::make('total_amount')
                    ->label('Total to Collect')
                    ->money('PKR')
                    ->sortable()
                    ->summarize(Sum::make()->money('PKR')->label('Grand Total')),
            ])
            ->filters([
                SelectFilter::make('id')
                    ->label('Filter by User')
                    ->options(User::pluck('name', 'id'))
                    ->searchable(),
                \Filament\Tables\Filters\Filter::make('month')
                    ->form([
                        Select::make('month')
                            ->label('Month')
                            ->options([
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! isset($data['month']) || ! $data['month']) {
                            return $query;
                        }

                        return $query->whereHas('hospitals.hospitalPayments', function ($q) use ($data) {
                            $q->whereMonth('month', $data['month']);
                        });
                    }),
                \Filament\Tables\Filters\Filter::make('year')
                    ->form([
                        Select::make('year')
                            ->label('Year')
                            ->options(function () {
                                $currentYear = now()->year;
                                $years = [];
                                for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
                                    $years[$i] = $i;
                                }

                                return $years;
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! isset($data['year']) || ! $data['year']) {
                            return $query;
                        }

                        return $query->whereHas('hospitals.hospitalPayments', function ($q) use ($data) {
                            $q->whereYear('month', $data['year']);
                        });
                    }),
                \Filament\Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('From Date'),
                        DatePicker::make('date_to')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'] ?? null,
                                fn(Builder $q, $date) => $q->whereHas('hospitals.hospitalPayments', function ($query) use ($date) {
                                    $query->whereDate('collection_date', '>=', $date);
                                })
                            )
                            ->when(
                                $data['date_to'] ?? null,
                                fn(Builder $q, $date) => $q->whereHas('hospitals.hospitalPayments', function ($query) use ($date) {
                                    $query->whereDate('collection_date', '<=', $date);
                                })
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->persistFiltersInSession()
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->defaultSort('name', 'asc')
            ->paginated([10, 25, 50, 100]);
    }
}
