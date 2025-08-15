<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250812 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 121, 'name' => 'Servicios Landing', 'tipo' => 'menu', 'id_relacion' => 29, 'guard_name' => 'web' ],
            ['id' => 115, 'name' => 'Servicios', 'tipo' => 'menu', 'id_relacion' => 28, 'guard_name' => 'web' ],
            ['id' => 120, 'name' => 'Configuración Landing Page', 'tipo' => 'seccion', 'id_relacion' => 16, 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}