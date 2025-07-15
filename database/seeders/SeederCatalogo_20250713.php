<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogo;

class SeederCatalogo_20250713 extends Seeder
{
    public function run(): void
    {
        $catalogos = [
            [
                'id' => 63,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-012',
                'catalogo_descripcion' => 'Grupo sanguíneo y Rh',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 62,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-011',
                'catalogo_descripcion' => 'Examen de heces',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 61,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-010',
                'catalogo_descripcion' => 'Serología (VDRL, HIV, etc.)',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 60,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-009',
                'catalogo_descripcion' => 'Prueba de embarazo',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 59,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-008',
                'catalogo_descripcion' => 'Coprológico',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 58,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-007',
                'catalogo_descripcion' => 'Ácido úrico',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 57,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-006',
                'catalogo_descripcion' => 'Creatinina',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 56,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-005',
                'catalogo_descripcion' => 'Urea',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 55,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-004',
                'catalogo_descripcion' => 'Triglicéridos',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 54,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-003',
                'catalogo_descripcion' => 'Colesterol total',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 53,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-002',
                'catalogo_descripcion' => 'Glucosa',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 52,
                'categoria_id' => 14,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'ES-001',
                'catalogo_descripcion' => 'Hemograma completo',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 51,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-007',
                'catalogo_descripcion' => 'Ninguno',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 50,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-006',
                'catalogo_descripcion' => '333',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],            [
                'id' => 49,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-005',
                'catalogo_descripcion' => 'Infección respiratoria aguda',
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