<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;

class SeederCatalogo_20250710 extends Seeder
{
    public function run(): void
    {
        $catalogos = [            [
                'id' => 48,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-004',
                'catalogo_descripcion' => 'ojeras',
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