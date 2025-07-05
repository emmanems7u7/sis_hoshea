<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('inventarios')->insert([
            [
                'imagen' => null,
                'nombre' => 'Diclofenaco 200mg',
                'descripcion' => 'Insumo médico para uso en pacientes',
                'categoria_id' => 7,
                'codigo' => 'IM-001',
                'stock_actual' => 50,
                'stock_minimo' => 20,
                'unidad_medida' => 'mg',
                'precio_unitario' => 1.00,
                'ubicacion' => 'Caja de medicamentos',
                'created_at' => '2025-06-27 01:58:31',
                'updated_at' => '2025-06-27 01:58:31',
            ],
            [
                'imagen' => 'uploads/imagenes/b0dcdb4b-0c98-4d5c-9c59-194a6a773f8f.jpg',
                'nombre' => 'Aspirina',
                'descripcion' => 'Insumo médico para pacientes',
                'categoria_id' => 7,
                'codigo' => 'IM-002',
                'stock_actual' => 50,
                'stock_minimo' => 10,
                'unidad_medida' => '20mg',
                'precio_unitario' => 1.00,
                'ubicacion' => 'Caja de medicamentos',
                'created_at' => '2025-06-27 02:06:30',
                'updated_at' => '2025-06-27 02:06:30',
            ],
        ]);
    }
}
