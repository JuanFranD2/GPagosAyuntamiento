<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cif_nif',
        'name',
        'address',
        'number',
        'letter',
        'staircase',
        'phone',
        'town_municipality',
        'province',
        'postal_code',
        'email',
    ];

    /**
     * Get the invoices associated with the client.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
