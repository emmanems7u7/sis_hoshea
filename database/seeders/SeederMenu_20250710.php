<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250710 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '23',
                'nombre' => 'Panel Artisan',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 12,
                'ruta' => 'artisan.index',
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