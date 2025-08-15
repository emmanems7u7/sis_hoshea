<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250813 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '30',
                'nombre' => 'Acerca de Landing',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 16,
                'ruta' => 'acerca_landings.index',
                'accion_usuario' => '',
            ],
        ];

        foreach ($menus as $data) {
            Menu::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}