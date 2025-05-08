<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Liquidation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_number',
        'liquidation_date',
        'concept',
        'taxable_base',
        'tax_rate',
        'total_fee',
        'bond',
        'total_to_pay',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'liquidation_date' => 'date',
        'taxable_base' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'bond' => 'decimal:2',
        'total_to_pay' => 'decimal:2',
    ];

    /**
     * Get the invoices associated with the liquidation.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
