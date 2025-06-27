<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'paciente_id',
        'tratamiento_id',
        'fecha_hora',
        'duracion',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',

    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(\App\Models\User::class, 'cita_user')
            ->withPivot('rol_en_cita')
            ->withTimestamps();
    }
}
