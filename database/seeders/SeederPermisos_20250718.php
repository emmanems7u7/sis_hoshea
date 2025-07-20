<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250718 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 115, 'name' => 'Servicios', 'tipo' => 'menu', 'id_relacion' => 26, 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}