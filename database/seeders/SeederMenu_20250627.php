<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250627 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '16',
                'nombre' => 'Tratamientos',
                'orden' => 3,
                'padre_id' => null,
                'seccion_id' => 11,
                'ruta' => 'tratamientos.index',
                'accion_usuario' => '',
            ],
            [
                'id' => '15',
                'nombre' => 'Citas',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 11,
                'ruta' => 'citas.index',
                'accion_usuario' => '',
            ],            [
                'id' => '14',
                'nombre' => 'Inventario',
                'orden' => 3,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'inventario.index',
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