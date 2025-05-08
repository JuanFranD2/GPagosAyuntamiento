<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceeding extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_number',
        'concept',
    ];

    // Si la clave primaria no es 'id', debes especificarlo:
    // protected $primaryKey = 'tu_clave_primaria';

    // Si no quieres que se gestionen las marcas de tiempo created_at y updated_at:
    // public $timestamps = false;
}
