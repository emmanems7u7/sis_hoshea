<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250627 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 100, 'name' => 'Tratamientos', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 99, 'name' => 'Citas', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 98, 'name' => 'citas.eliminar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 97, 'name' => 'citas.actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 96, 'name' => 'citas.editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 95, 'name' => 'citas.guardar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 94, 'name' => 'citas.crear', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 93, 'name' => 'citas.ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 92, 'name' => 'tratamientos.eliminar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 91, 'name' => 'tratamientos.actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 90, 'name' => 'tratamientos.editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 89, 'name' => 'tratamientos.guardar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 88, 'name' => 'tratamientos.crear', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 87, 'name' => 'tratamientos.ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 86, 'name' => 'Inventario', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => 85, 'name' => 'inventario.eliminar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 84, 'name' => 'inventario.actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 83, 'name' => 'inventario.editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 82, 'name' => 'inventario.guardar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 81, 'name' => 'inventario.crear', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => 80, 'name' => 'inventario.ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}