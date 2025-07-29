<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250726 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 117, 'name' => 'Inventario Clinica', 'tipo' => 'menu', 'id_relacion' => 27, 'guard_name' => 'web'],
            ['id' => 116, 'name' => 'tratamientos.finalizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}