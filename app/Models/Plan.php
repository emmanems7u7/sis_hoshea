<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes_tratamientos';

    protected $fillable = [
        'cita_id',
        'tipo',
        'descripcion',
    ];
}
