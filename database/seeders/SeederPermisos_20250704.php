<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250704 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 105, 'name' => 'configuracion.credenciales_actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 104, 'name' => 'credenciales_actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 103, 'name' => 'configuracion.credenciales_ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 102, 'name' => 'Configuración Credenciales', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 101, 'name' => 'citas.cambiar_estado', 'tipo' => 'permiso', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}