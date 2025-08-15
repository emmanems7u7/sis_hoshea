<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioLanding extends Model
{

    protected $fillable = [
        'titulo',
        'descripcion',
        'icono',
        'estado',
    ];

}
