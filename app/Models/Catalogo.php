<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $fillable = [
        'categoria_id',
        'catalogo_parent',
        'catalogo_codigo',
        'catalogo_descripcion',
        'catalogo_estado',
        'accion_usuario',
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
