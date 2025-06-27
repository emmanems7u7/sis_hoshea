<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'codigo',
        'stock_actual',
        'stock_minimo',
        'unidad_medida',
        'precio_unitario',
        'ubicacion',
        'imagen',
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
