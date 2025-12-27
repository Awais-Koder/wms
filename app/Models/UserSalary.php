<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSalary extends Model
{
    /** @use HasFactory<\Database\Factories\UserSalaryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'month',
        'base_salary',
        'commission_per_hospital',
        'hospitals_count',
        'commission_amount',
        'bonus',
        'deductions',
        'total_salary',
        'is_paid',
        'paid_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'month' => 'date',
            'base_salary' => 'decimal:2',
            'commission_per_hospital' => 'decimal:2',
            'hospitals_count' => 'integer',
            'commission_amount' => 'decimal:2',
            'bonus' => 'decimal:2',
            'deductions' => 'decimal:2',
            'total_salary' => 'decimal:2',
            'is_paid' => 'boolean',
            'paid_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
