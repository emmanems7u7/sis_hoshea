<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'id' => 1,
                'nombre' => 'Usuarios',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 5,
                'ruta' => 'users.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-09 20:08:42'),
                'updated_at' => Carbon::parse('2025-04-09 20:08:42'),
            ],
            [
                'id' => 2,
                'nombre' => 'Configuración de correo',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 6,
                'ruta' => 'configuracion.correo.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-11 18:44:37'),
                'updated_at' => Carbon::parse('2025-04-11 18:44:37'),
            ],
            [
                'id' => 3,
                'nombre' => 'Plantillas de Correo',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 6,
                'ruta' => 'correos.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-11 20:22:38'),
                'updated_at' => Carbon::parse('2025-04-11 20:22:38'),
            ],
            [
                'id' => 4,
                'nombre' => 'Gestión de Roles',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 7,
                'ruta' => 'roles.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-14 18:43:51'),
                'updated_at' => Carbon::parse('2025-04-14 18:43:51'),
            ],
            [
                'id' => 5,
                'nombre' => 'Gestión de Permisos',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 7,
                'ruta' => 'permissions.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-14 19:40:18'),
                'updated_at' => Carbon::parse('2025-04-14 19:40:18'),
            ],
            [
                'id' => 8,
                'nombre' => 'Configuración General',
                'orden' => 3,
                'padre_id' => null,
                'seccion_id' => 6,
                'ruta' => 'admin.configuracion.edit',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-04-15 17:38:07'),
                'updated_at' => Carbon::parse('2025-04-15 17:38:07'),
            ],
            [
                'id' => 11,
                'nombre' => 'Catálogo del Sistema',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'catalogos.index',
                'accion_usuario' => null,
                'created_at' => Carbon::parse('2025-05-02 18:38:43'),
                'updated_at' => Carbon::parse('2025-05-02 18:38:43'),
            ],
        ]);
    }
}
