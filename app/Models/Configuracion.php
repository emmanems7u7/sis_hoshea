<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = [
        'doble_factor_autenticacion',
        'limite_de_sesiones',
        'GROQ_API_KEY',
        'mantenimiento',
        'firma',
        'hoja_export',
        'dias_atencion',
        'titulo_servicio',
        'descripcion_servicio',
        'titulo_acercade',
        'descripcion_acercade',
        'roles_landing',
        'titulo_presentacion',
        'descripcion_presentacion',
        'direccion',
        'celular',
        'geolocalizacion'
    ];

    protected $table = 'configuracion';
    protected $guarded = [];
}
