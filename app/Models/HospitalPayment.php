<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hospital_id',
        'collected_by_user_id',
        'month',
        'payment_type',
        'collection_date',
        'amount',
        'paid_amount',
        'remaining_amount',
        'is_collected',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'hospital_id' => 'integer',
            'collected_by_user_id' => 'integer',
            'month' => 'date',
            'collection_date' => 'date',
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'is_collected' => 'boolean',
        ];
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by_user_id');
    }
}
