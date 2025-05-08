<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'invoice_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'representative_id', // Asegúrate de incluir el nuevo campo en fillable
        'liquidation_id',
        'user_id',
        'invoice_number',
    ];

    /**
     * Get the client (interesado) that owns the invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the representative client associated with the invoice.
     */
    public function representative(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'representative_id');
    }

    /**
     * Get the liquidation associated with the invoice.
     */
    public function liquidation(): BelongsTo
    {
        return $this->belongsTo(Liquidation::class);
    }

    /**
     * Get the user that created the invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
