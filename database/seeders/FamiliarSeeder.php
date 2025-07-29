<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Catalogo;
use Illuminate\Support\Str;
class FamiliarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la categoría "Familiar"
        $categoria = Categoria::create([
            'nombre' => 'Familiar',
            'descripcion' => 'Parentescos clínicamente relevantes para antecedentes familiares heredables.',
            'estado' => 1
        ]);

        // Familiares desde los cuales el paciente puede heredar enfermedades
        $familiaresHereditarios = [
            'Padre',
            'Madre',
            'Hermano',
            'Hermana',
            'Abuelo paterno',
            'Abuela paterna',
            'Abuelo materno',
            'Abuela materna',
            'Tío paterno',
            'Tía paterna',
            'Tío materno',
            'Tía materna',
            'Primo',
            'Prima'
        ];

        foreach ($familiaresHereditarios as $index => $descripcion) {
            Catalogo::create([
                'categoria_id' => $categoria->id,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'fam-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'catalogo_descripcion' => $descripcion,
                'catalogo_estado' => 1,
                'accion_usuario' => 'seeder_' . Str::random(5),
            ]);
        }
    }
}
