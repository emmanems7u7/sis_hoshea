<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuracion::create([
            'GROQ_API_KEY' => null,
            'doble_factor_autenticacion' => 0,
            'mantenimiento' => 0,
            'firma' => 1,
            'hoja_export' => 'default.xlsx',
            'limite_de_sesiones' => 1,
            'roles_landing' => json_encode(['admin']),
            'dias_atencion' => json_encode(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),

            'titulo_servicio' => 'Nuestros Servicios',
            'descripcion_servicio' => 'Ofrecemos soluciones innovadoras adaptadas a tus necesidades.',

            'titulo_acercade' => 'Acerca de Nosotros',
            'descripcion_acercade' => 'Somos una clínica dedicada a brindar atención de calidad.',

            'titulo_presentacion' => 'Bienvenido a Nuestra Clínica',
            'descripcion_presentacion' => 'Conoce nuestros servicios y especialistas.',

            'direccion' => 'Av. Siempre Viva 123',
            'celular' => '+59170000000',
            'geolocalizacion' => '-17.3936,-66.1570',

            'imagen_fondo' => 'imagenes/fondo.jpg',
            'logo_empresa' => 'imagenes/logo.png',
            'titulo_cabecera' => 'Clínica Salud Total',
            'descripcion_cabecera' => 'Cuidando tu salud, siempre.',
            'imagen_cabecera' => 'imagenes/cabecera.jpg',

            'titulo_emergencia' => 'Emergencias 24/7',
            'descripcion_emergencia' => 'Llámanos en caso de urgencia médica.',
        ]);
    }
}
