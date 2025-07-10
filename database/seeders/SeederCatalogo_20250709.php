<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;

class SeederCatalogo_20250709 extends Seeder
{
    public function run(): void
    {
        $catalogos = [
            [
                'id' => 15,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-002',
                'catalogo_descripcion' => 'diabetes',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],            [
                'id' => 14,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-001',
                'catalogo_descripcion' => 'epilepsia',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],];

        foreach ($catalogos as $data) {
            Catalogo::firstOrCreate(
                ['catalogo_codigo' => $data['catalogo_codigo']],
                $data
            );
        }
    }
}