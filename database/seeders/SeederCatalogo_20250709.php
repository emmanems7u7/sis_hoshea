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
                'id' => 47,
                'categoria_id' => 10,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'TP-003',
                'catalogo_descripcion' => 'Recomendaciones',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 46,
                'categoria_id' => 10,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'TP-002',
                'catalogo_descripcion' => 'Seguimiento',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 45,
                'categoria_id' => 10,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'TP-001',
                'catalogo_descripcion' => 'Estudios Complementarios',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 44,
                'categoria_id' => 6,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-003',
                'catalogo_descripcion' => 'asdf',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 43,
                'categoria_id' => 5,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'P-002',
                'catalogo_descripcion' => 'Perú',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 42,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-007',
                'catalogo_descripcion' => 'Otro',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 41,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-006',
                'catalogo_descripcion' => 'Examen Físico',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 40,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-005',
                'catalogo_descripcion' => 'Saturación O₂',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 39,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-004',
                'catalogo_descripcion' => 'Temperatura',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 38,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-003',
                'catalogo_descripcion' => 'Frecuencia respiratoria',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 37,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-002',
                'catalogo_descripcion' => 'Frecuencia cardíaca',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
            [
                'id' => 36,
                'categoria_id' => 9,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'Ob-001',
                'catalogo_descripcion' => 'Tensión arterial',
                'catalogo_estado' => '1',
                'accion_usuario' => 'admin',
            ],
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