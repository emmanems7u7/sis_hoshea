<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250728 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 119, 'name' => 'citas.guardar_gestion', 'tipo' => 'permiso', 'id_relacion' => , 'guard_name' => 'web' ],
            ['id' => 118, 'name' => 'tratamientos.agregar_observacion', 'tipo' => 'permiso', 'id_relacion' => , 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}