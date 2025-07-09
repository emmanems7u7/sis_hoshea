<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250709 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '22',
                'nombre' => 'Diagnosticos',
                'orden' => 6,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'diagnosticos.index',
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