<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;

class SeederSeccion_20250710 extends Seeder
{
    public function run(): void
    {
        $secciones = [            [
                'id' => 12,
                'titulo' => 'Administracion Artisan',
                'icono' => 'fas fa-paint-brush',
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