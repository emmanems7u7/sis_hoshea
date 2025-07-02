<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250701 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 104, 'name' => 'Lista pacientes', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 103, 'name' => 'ver tratamientos', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 78, 'name' => 'Ver Pacientes', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 101, 'name' => 'logica pacientes', 'tipo' => 'seccion', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}