<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250718 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '26',
                'nombre' => 'Servicios',
                'orden' => 4,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'servicios.index',
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