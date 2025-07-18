<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaExamen extends Model
{
    protected $fillable = [

        'cita_id',
        'examen',
        'examen_otro',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class, 'examen', 'catalogo_codigo');

    }
}
