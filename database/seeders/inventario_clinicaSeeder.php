<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Bien;
use Carbon\Carbon;

class inventario_clinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías
        $categoriasData = [
            ['nombre' => 'Muebles', 'descripcion' => 'Sillas, escritorios, mesas, camas'],
            ['nombre' => 'Equipos Médicos', 'descripcion' => 'Equipos para diagnóstico y tratamiento'],
            ['nombre' => 'Electrónicos', 'descripcion' => 'Televisores, computadoras, impresoras'],
            ['nombre' => 'Insumos', 'descripcion' => 'Materiales y suministros descartables'],
            ['nombre' => 'Instrumental', 'descripcion' => 'Instrumentos médicos'],
        ];

        $categorias = [];
        foreach ($categoriasData as $catData) {
            $categorias[$catData['nombre']] = Categoria::create($catData);
        }

        // Crear bienes asociados a las categorías
        $biens = [
            [
                'categoria' => 'Muebles',
                'nombre' => 'Silla de Oficina',
                'foto' => 'silla_oficina.jpg',
                'descripcion' => 'Silla ergonómica con ruedas',
                'cantidad' => 20,
                'ubicacion' => 'Sala de Espera',
                'fecha_adquisicion' => Carbon::parse('2020-01-15'),
                'valor_adquisicion' => 120.50,
            ],
            [
                'categoria' => 'Muebles',
                'nombre' => 'Cama de Hospital',
                'foto' => 'cama_hospital.jpg',
                'descripcion' => 'Cama ajustable con barandas laterales',
                'cantidad' => 15,
                'ubicacion' => 'Habitaciones',
                'fecha_adquisicion' => Carbon::parse('2019-05-10'),
                'valor_adquisicion' => 550.00,
            ],
            [
                'categoria' => 'Equipos Médicos',
                'nombre' => 'Monitor de Signos Vitales',
                'foto' => 'monitor_signos.jpg',
                'descripcion' => 'Monitor para frecuencia cardíaca, presión arterial, oxígeno',
                'cantidad' => 5,
                'ubicacion' => 'UCI',
                'fecha_adquisicion' => Carbon::parse('2021-08-01'),
                'valor_adquisicion' => 2000.00,
            ],
            [
                'categoria' => 'Electrónicos',
                'nombre' => 'Computadora de Escritorio',
                'foto' => 'computadora.jpg',
                'descripcion' => 'PC para uso administrativo',
                'cantidad' => 10,
                'ubicacion' => 'Oficina Administrativa',
                'fecha_adquisicion' => Carbon::parse('2022-03-20'),
                'valor_adquisicion' => 800.00,
            ],
            [
                'categoria' => 'Electrónicos',
                'nombre' => 'Televisor LED 42"',
                'foto' => 'televisor_led.jpg',
                'descripcion' => 'Televisor para sala de espera',
                'cantidad' => 2,
                'ubicacion' => 'Sala de Espera',
                'fecha_adquisicion' => Carbon::parse('2020-11-10'),
                'valor_adquisicion' => 350.00,
            ],
            [
                'categoria' => 'Insumos',
                'nombre' => 'Guantes de Látex',
                'foto' => 'guantes_latex.jpg',
                'descripcion' => 'Caja con 100 guantes desechables',
                'cantidad' => 100,
                'ubicacion' => 'Almacén',
                'fecha_adquisicion' => Carbon::parse('2023-01-05'),
                'valor_adquisicion' => 15.00,
            ],
            [
                'categoria' => 'Instrumental',
                'nombre' => 'Estetoscopio',
                'foto' => 'estetoscopio.jpg',
                'descripcion' => 'Instrumento para auscultar sonidos corporales',
                'cantidad' => 25,
                'ubicacion' => 'Consultorios',
                'fecha_adquisicion' => Carbon::parse('2021-06-15'),
                'valor_adquisicion' => 40.00,
            ],
        ];

        foreach ($biens as $bien) {
            Bien::create([
                'categoria_id' => $categorias[$bien['categoria']]->id,
                'nombre' => $bien['nombre'],
                'foto' => $bien['foto'],
                'descripcion' => $bien['descripcion'],
                'cantidad' => $bien['cantidad'],
                'ubicacion' => $bien['ubicacion'],
                'fecha_adquisicion' => $bien['fecha_adquisicion'],
                'valor_adquisicion' => $bien['valor_adquisicion'],
            ]);
        }
    }
}
