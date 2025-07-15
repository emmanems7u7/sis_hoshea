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
        'primera_cita',
        'gestionado',
        'fecha_gestion'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'fecha_gestion' => 'datetime',
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
    public function diagnostico()
    {
        // Diagnóstico se relaciona con Tratamiento, no con Cita directamente,

        return $this->hasOne(Diagnostico::class, 'tratamiento_id', 'tratamiento_id');
    }
    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'tratamiento_id', 'tratamiento_id');
    }

    // Planes (uno a muchos)
    public function planes()
    {
        return $this->hasMany(Plan::class, 'cita_id', 'id');
    }

    // DatosCita (uno a muchos)
    public function datosCita()
    {
        return $this->hasMany(DatoCita::class, 'cita_id', 'id');
    }

    // ObjetivosCita (uno a muchos)
    public function objetivosCita()
    {
        return $this->hasMany(ObjetivoCita::class, 'cita_id', 'id');
    }

    public function examenes()
    {
        return $this->hasMany(CitaExamen::class);
    }
}
