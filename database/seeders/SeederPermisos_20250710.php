<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250710 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 109, 'name' => 'Panel Artisan', 'tipo' => 'menu', 'id_relacion' => 23, 'guard_name' => 'web'],
            ['id' => 108, 'name' => 'Administracion Artisan', 'tipo' => 'seccion', 'id_relacion' => 12, 'guard_name' => 'web'],
            ['id' => 107, 'name' => 'ejecutar-artisan', 'tipo' => 'permiso', 'id_relacion' => 0, 'guard_name' => 'web'],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}