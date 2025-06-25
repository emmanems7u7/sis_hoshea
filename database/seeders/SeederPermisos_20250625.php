<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250625 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 78, 'name' => 'Ver Pacientes', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 77, 'name' => 'Pacientes', 'tipo' => 'seccion', 'guard_name' => 'web' ],
            ['id' => 76, 'name' => 'pacientes.eliminar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 75, 'name' => 'pacientes.actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 74, 'name' => 'pacientes.editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 73, 'name' => 'pacientes.ver_detalle', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 72, 'name' => 'pacientes.guardar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 71, 'name' => 'pacientes.crear', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 70, 'name' => 'pacientes.ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}