<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250812 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '29',
                'nombre' => 'Servicios Landing',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 16,
                'ruta' => 'servicios_landing.index',
                'accion_usuario' => '',
            ]
        ];

        foreach ($menus as $data) {
            Menu::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}