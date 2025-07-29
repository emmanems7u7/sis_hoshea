<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacienteAntecedente extends Model
{
    protected $table = 'paciente_antecedente';

    protected $fillable = [
        'paciente_id',
        'antecedente',
        'familiar'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function catalogoAntecedente()
    {
        return $this->belongsTo(Catalogo::class, 'antecedente', 'catalogo_codigo');
    }

    public function catalogoFamiliar()
    {
        return $this->belongsTo(Catalogo::class, 'familiar', 'catalogo_codigo');
    }
}
