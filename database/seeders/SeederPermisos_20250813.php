<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250813 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 122, 'name' => 'Acerca de Landing', 'tipo' => 'menu', 'id_relacion' => 30, 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}