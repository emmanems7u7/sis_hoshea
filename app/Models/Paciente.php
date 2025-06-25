<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'genero',
        'telefono_fijo',
        'telefono_movil',
        'email',
        'direccion',
        'tipo_documento',
        'numero_documento',
        'pais',
        'departamento',
        'ciudad',
        'activo',
    ];

    // Cast para atributos específicos
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    public function paisCatalogo()
    {
        return $this->belongsTo(Catalogo::class, 'pais', 'catalogo_codigo');
    }

    public function ciudadCatalogo()
    {
        return $this->belongsTo(Catalogo::class, 'ciudad', 'catalogo_codigo');
    }
}
