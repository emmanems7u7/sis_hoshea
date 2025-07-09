<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250704 extends Seeder
{
    public function run(): void
    {
        $menus = [            [
                'id' => '17',
                'nombre' => 'Configuración Credenciales',
                'orden' => 4,
                'padre_id' => null,
                'seccion_id' => 6,
                'ruta' => 'configuracion.credenciales.index',
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