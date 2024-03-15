<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puestos extends Model
{
    use HasFactory;

    protected $table = 'puestos';
    
    protected $fillable = [
        'id_area',
        'puesto',
        'descripcion',
    ];
}
