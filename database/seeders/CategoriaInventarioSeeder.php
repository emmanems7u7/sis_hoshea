<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Support\Str;

class CategoriaInventarioSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        // Crear categorías clínicas si no existen
        $categorias = [
            ['nombre' => 'Medicamentos', 'descripcion' => 'Fármacos para tratamientos médicos', 'estado' => 1],
            ['nombre' => 'Insumos Médicos', 'descripcion' => 'Materiales desechables y reutilizables', 'estado' => 1],
            ['nombre' => 'Equipos Médicos', 'descripcion' => 'Equipos usados en procedimientos', 'estado' => 1],
            ['nombre' => 'Material de Curación', 'descripcion' => 'Gasas, vendas, alcohol, etc.', 'estado' => 1],
            ['nombre' => 'Vacunas', 'descripcion' => 'Vacunas preventivas', 'estado' => 1],
            ['nombre' => 'Suplementos', 'descripcion' => 'Vitaminas y suplementos alimenticios', 'estado' => 1],
            ['nombre' => 'Instrumental Médico', 'descripcion' => 'Herramientas médicas básicas', 'estado' => 1],
            ['nombre' => 'Limpieza y Desinfección', 'descripcion' => 'Productos para esterilización', 'estado' => 1],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(
                ['nombre' => $cat['nombre']],
                ['descripcion' => $cat['descripcion'], 'estado' => $cat['estado']]
            );
        }

        // Lista de productos por categoría
        $data = [
            'Medicamentos' => [
                'Paracetamol',
                'Ibuprofeno',
                'Amoxicilina',
                'Metformina',
                'Loratadina',
                'Diclofenaco',
                'Omeprazol',
                'Aspirina',
                'Losartán',
                'Prednisona'
            ],
            'Insumos Médicos' => [
                'Jeringas 5ml',
                'Guantes de látex',
                'Mascarillas quirúrgicas',
                'Batas descartables',
                'Alcohol isopropílico',
                'Gorros descartables',
                'Bisturís',
                'Algodón absorbente'
            ],
            'Equipos Médicos' => [
                'Tensiómetro digital',
                'Estetoscopio',
                'Termómetro infrarrojo',
                'Oxímetro',
                'Nebulizador',
                'Balanza médica',
                'Lámpara de exploración',
                'Otoscopio'
            ],
            'Material de Curación' => [
                'Gasas estériles',
                'Vendas elásticas',
                'Cinta microporosa',
                'Alcohol al 70%',
                'Povidona yodada',
                'Esparadrapo',
                'Compresas frías',
                'Tijeras médicas'
            ],
            'Vacunas' => [
                'BCG',
                'Hepatitis B',
                'Pentavalente',
                'Influenza',
                'Antitetánica',
                'COVID-19 Pfizer',
                'Sarampión',
                'Varicela',
                'Rotavirus',
                'Neumococo'
            ],
            'Suplementos' => [
                'Vitamina C',
                'Hierro',
                'Zinc',
                'Calcio + D3',
                'Complejo B',
                'Omega 3',
                'Ácido fólico',
                'Magnesio',
                'Multivitamínico adulto',
                'Vitamina D'
            ],
            'Instrumental Médico' => [
                'Pinzas de disección',
                'Pinzas Kelly',
                'Portaagujas',
                'Separadores Farabeuf',
                'Curetas',
                'Espejo laríngeo',
                'Tijeras Mayo',
                'Pinzas Allis'
            ],
            'Limpieza y Desinfección' => [
                'Cloro diluido',
                'Solución antiséptica',
                'Jabón quirúrgico',
                'Toallas desinfectantes',
                'Autoclave portátil',
                'Bandeja esterilizable',
                'Cepillo de limpieza',
                'Cubetas'
            ],
        ];

        // Crear inventarios por categoría
        foreach ($data as $categoriaNombre => $productos) {
            $categoria = Categoria::where('nombre', $categoriaNombre)->first();

            if ($categoria) {
                foreach ($productos as $producto) {
                    Inventario::create([
                        'nombre' => $producto,
                        'descripcion' => $faker->sentence(5),
                        'categoria_id' => $categoria->id,
                        'codigo' => strtoupper(Str::random(8)),
                        'stock_actual' => rand(10, 200),
                        'stock_minimo' => rand(5, 20),
                        'unidad_medida' => $faker->randomElement(['unidad', 'caja', 'frasco', 'tabletas', 'ampollas']),
                        'precio_unitario' => $faker->randomFloat(2, 5, 500),
                        'ubicacion' => 'Estante ' . rand(1, 4) . ' - Nivel ' . rand(1, 3),
                        'imagen' => null,
                    ]);
                }
            }
        }
    }
}
