<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjetivoCita extends Model
{
    protected $fillable = [
        'cita_id',
        'codigo',
        'valor',
    ];

}
