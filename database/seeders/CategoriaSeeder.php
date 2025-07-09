<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('categorias')->insert([
            [
                'id' => 3,
                'nombre' => 'Ciudades',
                'descripcion' => 'Listado de ciudades',
                'estado' => 1,
                'created_at' => '2025-06-25 01:29:09',
                'updated_at' => '2025-06-25 01:29:09',
            ],
            [
                'id' => 4,
                'nombre' => 'Inactiva',
                'descripcion' => 'Ejemplo de categoría deshabilitada',
                'estado' => 0,
                'created_at' => '2025-06-25 01:29:09',
                'updated_at' => '2025-06-25 01:29:09',
            ],
            [
                'id' => 5,
                'nombre' => 'Paises',
                'descripcion' => 'Lista de paises disponibles',
                'estado' => 1,
                'created_at' => '2025-06-25 02:43:10',
                'updated_at' => '2025-06-25 02:43:10',
            ],
            [
                'id' => 7,
                'nombre' => 'Insumos Medicos',
                'descripcion' => 'Descripcion',
                'estado' => 1,
                'created_at' => '2025-06-27 01:53:37',
                'updated_at' => '2025-06-27 01:53:37',
            ],
            [
                'id' => 8,
                'nombre' => 'Departamentos',
                'descripcion' => 'Categoria de departamentos',
                'estado' => 1,
                'created_at' => '2025-07-02 23:03:43',
                'updated_at' => '2025-07-02 23:03:43',
            ],
        ]);
    }
}
