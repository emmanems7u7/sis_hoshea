<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250726 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '27',
                'nombre' => 'Inventario Clinica',
                'orden' => 5,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'biens.index',
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