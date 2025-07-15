<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class SeederCategoria_20250713 extends Seeder
{
    public function run(): void
    {
        $categorias = [            [
                'id' => 14,
                'nombre' => 'Exámenes Solcitados',
                'descripcion' => 'Lista de examenes',
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