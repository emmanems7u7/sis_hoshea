<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    protected $fillable = [
        'categoria_id',
        'nombre',
        'foto',
        'descripcion',
        'codigo_inventario',
        'cantidad',
        'ubicacion',
        'fecha_adquisicion',
        'valor_adquisicion',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
