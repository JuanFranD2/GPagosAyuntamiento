<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bank_name',
        'account_number',
    ];

    /**
     * Get all the invoices associated with this payment method.
     */
    public function invoices(): HasMany
    {
        // Asumiendo que la clave foránea en la tabla invoices es 'payment_method_id'
        return $this->hasMany(Invoice::class, 'payment_method_id');
    }

    /**
     * Get all the liquidations associated with this payment method.
     * // NOTA: ¿La tabla liquidations tiene un campo payment_method_id?
     * // La migración de liquidations que me mostraste no lo tenía.
     */
    public function liquidations(): HasMany
    {
        // Si la tabla liquidations no tiene payment_method_id, esta relación no funcionará
        return $this->hasMany(Liquidation::class, 'payment_method_id');
    }
}
