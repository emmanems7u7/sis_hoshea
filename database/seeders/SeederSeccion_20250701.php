<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;

class SeederSeccion_20250701 extends Seeder
{
    public function run(): void
    {
        $secciones = [            [
                'id' => '12',
                'titulo' => 'logica pacientes',
                'icono' => 'fas fa-user-injured',
                'posicion' => 6,
                'accion_usuario' => 'admin',
            ],];

        foreach ($secciones as $data) {
            Seccion::firstOrCreate(
                ['titulo' => $data['titulo']],
                $data
            );
        }
    }
}