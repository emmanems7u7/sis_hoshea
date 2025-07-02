<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250701 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '20',
                'nombre' => 'Lista pacientes',
                'orden' => 4,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'pacientes.index',
                'accion_usuario' => '',
            ],
            [
                'id' => '19',
                'nombre' => 'ver tratamientos',
                'orden' => 3,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'tratamientos.index',
                'accion_usuario' => '',
            ],
            [
                'id' => '18',
                'nombre' => 'ver pacientes',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'pacientes.index',
                'accion_usuario' => '',
            ],            [
                'id' => '17',
                'nombre' => 'pacientes',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'pacientes.index',
                'accion_usuario' => '',
            ],];

        foreach ($menus as $data) {
            Menu::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}