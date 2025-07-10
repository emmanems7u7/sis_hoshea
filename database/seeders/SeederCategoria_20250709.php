<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class SeederCategoria_20250709 extends Seeder
{
    public function run(): void
    {
        $categorias = [            [
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