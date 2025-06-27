<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250625 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '13',
                'nombre' => 'Categorias',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'categorias.index',
                'accion_usuario' => '',
            ],            [
                'id' => '12',
                'nombre' => 'Ver Pacientes',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 11,
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