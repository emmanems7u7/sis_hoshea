<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $fillable = [
        'tratamiento_id',
        'cod_diagnostico',
        'fecha_diagnostico',
        'estado',
        'criterio_clinico',
        'evolucion_diagnostico',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }
    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class, 'cod_diagnostico', 'catalogo_codigo');

    }
}

