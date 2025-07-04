<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250703 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '21',
                'nombre' => 'Ver Citas',
                'orden' => 5,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'citas.index',
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