<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class SeederCategoria_20250709 extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'id' => 10,
                'nombre' => 'tipo_plan',
                'descripcion' => 'listado de tipos de planes para la cita',
                'estado' => '1',
            ],
            [
                'id' => 9,
                'nombre' => 'Objetivos',
                'descripcion' => 'Listado de objetivos para una cita',
                'estado' => '1',
            ],            [
                'id' => '6',
                'nombre' => 'Diagnosticos',
                'descripcion' => 'listado de diagnosticos',
                'estado' => '1',
            ],];

        foreach ($categorias as $data) {
            Categoria::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}