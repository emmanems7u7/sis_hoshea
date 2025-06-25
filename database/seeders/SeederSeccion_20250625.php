<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;

class SeederSeccion_20250625 extends Seeder
{
    public function run(): void
    {
        $secciones = [            [
                'id' => '11',
                'titulo' => 'Pacientes',
                'icono' => 'fas fa-users',
                'posicion' => 5,
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