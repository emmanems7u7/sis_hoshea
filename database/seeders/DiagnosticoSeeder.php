<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Catalogo;
class DiagnosticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categoria = Categoria::where('nombre', 'Diagnosticos')->first();
        // Lista de antecedentes reales
        $antecedentes = [
            'Diabetes tipo 1',
            'Diabetes tipo 2',
            'Hipertensión arterial',
            'Cardiopatía isquémica',
            'Infarto agudo de miocardio',
            'Accidente cerebrovascular (ACV)',
            'Cáncer de mama',
            'Cáncer de próstata',
            'Cáncer de colon',
            'Cáncer de pulmón',
            'Cáncer gástrico',
            'Cáncer de páncreas',
            'Asma bronquial',
            'Enfermedad pulmonar obstructiva crónica (EPOC)',
            'Alergias alimentarias',
            'Alergias ambientales',
            'Lupus eritematoso sistémico',
            'Artritis reumatoide',
            'Esclerosis múltiple',
            'Hipotiroidismo',
            'Hipertiroidismo',
            'Enfermedad de Crohn',
            'Colitis ulcerativa',
            'Enfermedad celíaca',
            'Fibromialgia',
            'Trastorno bipolar',
            'Esquizofrenia',
            'Depresión mayor',
            'Trastorno de ansiedad generalizada',
            'Trastorno obsesivo compulsivo (TOC)',
            'Trastorno del espectro autista',
            'Epilepsia',
            'Enfermedad de Alzheimer',
            'Enfermedad de Parkinson',
            'Síndrome de Down',
            'Síndrome de Marfan',
            'Talasemia',
            'Hemofilia',
            'Obesidad',
            'Hipercolesterolemia familiar',
            'Insuficiencia renal crónica',
            'Glaucoma',
            'Degeneración macular',
            'Sordera congénita',
            'Malformaciones cardíacas congénitas',
            'Tuberculosis',
            'VIH/SIDA',
            'Hepatitis B',
            'Hepatitis C',
            'Abuso de sustancias (alcohol, drogas)'
        ];

        // Crear registros en la tabla catalogos
        foreach ($antecedentes as $index => $descripcion) {
            Catalogo::create([
                'categoria_id' => $categoria->id,
                'catalogo_parent' => null,
                'catalogo_codigo' => 'diag-' . str_pad($index + 8, 3, '0', STR_PAD_LEFT),
                'catalogo_descripcion' => $descripcion,
                'catalogo_estado' => 1,
                'accion_usuario' => 'seeder_',
            ]);
        }
    }
}
