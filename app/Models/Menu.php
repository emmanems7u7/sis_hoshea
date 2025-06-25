<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{


    protected $fillable = ['nombre', 'orden', 'padre_id', 'seccion_id', 'ruta', 'accion_usuario'];
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }
}
