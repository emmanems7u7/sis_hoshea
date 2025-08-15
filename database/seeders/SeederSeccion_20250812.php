<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;

class SeederSeccion_20250812 extends Seeder
{
    public function run(): void
    {
        $secciones = [
            [
                'id' => 16,
                'titulo' => 'Configuración Landing Page',
                'icono' => 'fas fa-cog',
                'posicion' => 9,
                'accion_usuario' => 'admin',
            ],
        ];

        foreach ($secciones as $data) {
            Seccion::firstOrCreate(
                ['titulo' => $data['titulo']],
                $data
            );
        }
    }
}