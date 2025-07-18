<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('permissions')->insert([
            ['id' => 5, 'name' => 'Administración de Usuarios', 'tipo' => 'seccion', 'id_relacion' => 5, 'guard_name' => 'web', 'created_at' => '2025-04-14 20:07:34', 'updated_at' => '2025-04-14 20:07:34'],
            ['id' => 6, 'name' => 'Configuración', 'tipo' => 'seccion', 'id_relacion' => 6, 'guard_name' => 'web', 'created_at' => '2025-04-14 20:13:26', 'updated_at' => '2025-04-14 20:14:36'],
            ['id' => 7, 'name' => 'Roles y Permisos', 'tipo' => 'seccion', 'id_relacion' => 7, 'guard_name' => 'web', 'created_at' => '2025-04-14 20:13:32', 'updated_at' => '2025-04-14 20:13:32'],
            ['id' => 11, 'name' => 'Usuarios', 'tipo' => 'menu', 'id_relacion' => 1, 'guard_name' => 'web', 'created_at' => '2025-04-14 20:46:59', 'updated_at' => '2025-04-14 20:46:59'],
            ['id' => 12, 'name' => 'Configuración de correo', 'tipo' => 'menu', 'id_relacion' => 2, 'guard_name' => 'web', 'created_at' => '2025-04-14 21:12:16', 'updated_at' => '2025-04-14 21:12:16'],
            ['id' => 13, 'name' => 'Plantillas de Correo', 'tipo' => 'menu', 'id_relacion' => 3, 'guard_name' => 'web', 'created_at' => '2025-04-14 21:12:21', 'updated_at' => '2025-04-14 21:12:21'],
            ['id' => 14, 'name' => 'Gestión de Roles', 'tipo' => 'menu', 'id_relacion' => 4, 'guard_name' => 'web', 'created_at' => '2025-04-14 21:12:27', 'updated_at' => '2025-04-14 21:12:27'],
            ['id' => 15, 'name' => 'Gestión de Permisos', 'tipo' => 'menu', 'id_relacion' => 5, 'guard_name' => 'web', 'created_at' => '2025-04-14 21:12:31', 'updated_at' => '2025-04-14 21:12:31'],
            ['id' => 16, 'name' => 'Configuración General', 'tipo' => 'menu', 'id_relacion' => 8, 'guard_name' => 'web', 'created_at' => '2025-04-15 17:38:07', 'updated_at' => '2025-04-15 17:38:07'],
            ['id' => 17, 'name' => 'usuarios.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:12:43', 'updated_at' => '2025-04-15 18:12:43'],
            ['id' => 18, 'name' => 'usuarios.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:12:53', 'updated_at' => '2025-04-15 18:12:53'],
            ['id' => 19, 'name' => 'usuarios.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:13:30', 'updated_at' => '2025-04-15 18:13:30'],
            ['id' => 20, 'name' => 'usuarios.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:13:44', 'updated_at' => '2025-04-15 18:13:44'],
            ['id' => 21, 'name' => 'usuarios.perfil', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:14:04', 'updated_at' => '2025-04-15 18:15:23'],
            ['id' => 22, 'name' => 'configuracion_correo.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:37:01', 'updated_at' => '2025-04-15 21:11:25'],
            ['id' => 24, 'name' => 'roles.inicio', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:43:45', 'updated_at' => '2025-04-15 18:43:45'],
            ['id' => 25, 'name' => 'roles.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:01', 'updated_at' => '2025-04-15 18:44:01'],
            ['id' => 26, 'name' => 'roles.store', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:11', 'updated_at' => '2025-04-15 18:44:11'],
            ['id' => 27, 'name' => 'roles.guardar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:24', 'updated_at' => '2025-04-15 18:44:24'],
            ['id' => 28, 'name' => 'roles.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:34', 'updated_at' => '2025-04-15 18:44:34'],
            ['id' => 29, 'name' => 'roles.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:44', 'updated_at' => '2025-04-15 18:44:44'],
            ['id' => 30, 'name' => 'roles.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:44:54', 'updated_at' => '2025-04-15 18:44:54'],
            ['id' => 31, 'name' => 'permisos.inicio', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:45:04', 'updated_at' => '2025-04-15 18:46:27'],
            ['id' => 32, 'name' => 'permisos.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:45:47', 'updated_at' => '2025-04-15 18:45:47'],
            ['id' => 33, 'name' => 'permisos.guardar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:46:37', 'updated_at' => '2025-04-15 18:46:37'],
            ['id' => 34, 'name' => 'permisos.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:46:44', 'updated_at' => '2025-04-15 18:46:44'],
            ['id' => 35, 'name' => 'permisos.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:46:50', 'updated_at' => '2025-04-15 18:46:50'],
            ['id' => 36, 'name' => 'permisos.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 18:47:01', 'updated_at' => '2025-04-15 18:47:01'],
            ['id' => 37, 'name' => 'configuracion.inicio', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 19:05:58', 'updated_at' => '2025-04-15 19:05:58'],
            ['id' => 38, 'name' => 'configuracion.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 19:06:04', 'updated_at' => '2025-04-15 19:06:04'],
            ['id' => 39, 'name' => 'plantillas.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 21:06:07', 'updated_at' => '2025-04-15 21:06:07'],
            ['id' => 40, 'name' => 'plantillas.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 21:07:07', 'updated_at' => '2025-04-15 21:07:07'],
            ['id' => 41, 'name' => 'configuracion_correo.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-04-15 21:12:26', 'updated_at' => '2025-04-15 21:12:26'],
            ['id' => 52, 'name' => 'verify', 'tipo' => 'menu', 'id_relacion' => 10, 'guard_name' => 'web', 'created_at' => '2025-05-02 18:26:48', 'updated_at' => '2025-05-02 18:26:48'],
            ['id' => 53, 'name' => 'Administración y Parametrización', 'tipo' => 'seccion', 'id_relacion' => 10, 'guard_name' => 'web', 'created_at' => '2025-05-02 18:38:17', 'updated_at' => '2025-05-02 18:38:17'],
            ['id' => 54, 'name' => 'Catálogo del Sistema', 'tipo' => 'menu', 'id_relacion' => 11, 'guard_name' => 'web', 'created_at' => '2025-05-02 18:38:43', 'updated_at' => '2025-05-02 18:38:43'],
            ['id' => 55, 'name' => 'catalogo.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:47:16', 'updated_at' => '2025-05-07 19:47:16'],
            ['id' => 56, 'name' => 'catalogo.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:47:27', 'updated_at' => '2025-05-07 19:47:27'],
            ['id' => 57, 'name' => 'catalogo.guardar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:47:52', 'updated_at' => '2025-05-07 19:47:52'],
            ['id' => 58, 'name' => 'catalogo.ver_detalle', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:48:02', 'updated_at' => '2025-05-07 19:48:02'],
            ['id' => 59, 'name' => 'catalogo.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:48:10', 'updated_at' => '2025-05-07 19:48:10'],
            ['id' => 60, 'name' => 'catalogo.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:48:18', 'updated_at' => '2025-05-07 19:48:18'],
            ['id' => 62, 'name' => 'catalogo.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:48:43', 'updated_at' => '2025-05-07 19:48:43'],
            ['id' => 63, 'name' => 'categoria.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:24', 'updated_at' => '2025-05-07 19:51:24'],
            ['id' => 64, 'name' => 'categoria.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:32', 'updated_at' => '2025-05-07 19:51:32'],
            ['id' => 65, 'name' => 'categoria.guardar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:38', 'updated_at' => '2025-05-07 19:51:38'],
            ['id' => 66, 'name' => 'categoria.ver_detalle', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:45', 'updated_at' => '2025-05-07 19:51:45'],
            ['id' => 67, 'name' => 'categoria.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:54', 'updated_at' => '2025-05-07 19:51:54'],
            ['id' => 68, 'name' => 'categoria.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:54', 'updated_at' => '2025-05-07 19:51:54'],
            ['id' => 69, 'name' => 'categoria.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web', 'created_at' => '2025-05-07 19:51:54', 'updated_at' => '2025-05-07 19:51:54'],

        ]);


        DB::table('role_has_permissions')->insert([
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 2],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],
            ['permission_id' => 14, 'role_id' => 1],
            ['permission_id' => 15, 'role_id' => 1],
            ['permission_id' => 16, 'role_id' => 1],
            ['permission_id' => 17, 'role_id' => 1],
            ['permission_id' => 17, 'role_id' => 2],
            ['permission_id' => 18, 'role_id' => 1],
            ['permission_id' => 18, 'role_id' => 2],
            ['permission_id' => 19, 'role_id' => 1],
            ['permission_id' => 19, 'role_id' => 2],
            ['permission_id' => 20, 'role_id' => 1],
            ['permission_id' => 20, 'role_id' => 2],
            ['permission_id' => 21, 'role_id' => 1],
            ['permission_id' => 21, 'role_id' => 2],
            ['permission_id' => 22, 'role_id' => 1],
            ['permission_id' => 24, 'role_id' => 1],
            ['permission_id' => 25, 'role_id' => 1],
            ['permission_id' => 26, 'role_id' => 1],
            ['permission_id' => 27, 'role_id' => 1],
            ['permission_id' => 28, 'role_id' => 1],
            ['permission_id' => 29, 'role_id' => 1],
            ['permission_id' => 30, 'role_id' => 1],
            ['permission_id' => 31, 'role_id' => 1],
            ['permission_id' => 32, 'role_id' => 1],
            ['permission_id' => 33, 'role_id' => 1],
            ['permission_id' => 34, 'role_id' => 1],
            ['permission_id' => 35, 'role_id' => 1],
            ['permission_id' => 36, 'role_id' => 1],
            ['permission_id' => 37, 'role_id' => 1],
            ['permission_id' => 38, 'role_id' => 1],
            ['permission_id' => 39, 'role_id' => 1],
            ['permission_id' => 41, 'role_id' => 1],
            ['permission_id' => 53, 'role_id' => 1],
            ['permission_id' => 54, 'role_id' => 1],
            ['permission_id' => 56, 'role_id' => 1],
            ['permission_id' => 57, 'role_id' => 1],
            ['permission_id' => 58, 'role_id' => 1],
            ['permission_id' => 59, 'role_id' => 1],
            ['permission_id' => 60, 'role_id' => 1],
            ['permission_id' => 62, 'role_id' => 1],
            ['permission_id' => 63, 'role_id' => 1],
            ['permission_id' => 64, 'role_id' => 1],
            ['permission_id' => 65, 'role_id' => 1],
            ['permission_id' => 66, 'role_id' => 1],
            ['permission_id' => 67, 'role_id' => 1],
            ['permission_id' => 68, 'role_id' => 1],
            ['permission_id' => 69, 'role_id' => 1],
        ]);
    }

}
