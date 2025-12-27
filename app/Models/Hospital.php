<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'country_id',
        'district_id',
        'tehsil_id',
        'city_id',
        'user_id',
        'address',
        'doctor_name',
        'cnic',
        'amount',
        'agreement_duration',
        'date',
        'phc_number',
        'mobile_number_1',
        'mobile_number_2',
        'agreement_image',
        'province_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'country_id' => 'integer',
            'district_id' => 'integer',
            'tehsil_id' => 'integer',
            'city_id' => 'integer',
            'user_id' => 'integer',
            'amount' => 'decimal:2',
            'date' => 'date',
            'province_id' => 'integer',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil(): BelongsTo
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hospitalPayments(): HasMany
    {
        return $this->hasMany(HospitalPayment::class);
    }
}
