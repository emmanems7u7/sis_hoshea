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
            'doble_factor_autenticacion' => false,
            'limite_de_sesiones' => 1,
            'GROQ_API_KEY' => null,
            'mantenimiento' => false,
            'firma' => '',
            'hoja_export' => '',
            'dias_atencion' => '',
            'titulo_servicio' => '',
            'descripcion_servicio' => '',
            'titulo_acercade' => '',
            'descripcion_acercade' => '',
            'roles_landing' => '',
            'titulo_presentacion' => '',
            'descripcion_presentacion' => '',
            'direccion' => '',
            'celular' => '',
            'geolocalizacion' => '',

            'imagen_fondo' => '',
            'logo_empresa' => '',
            'titulo_cabecera' => '',
            'descripcion_cabecera' => '',
            'imagen_cabecera' => '',

            'titulo_emergencia' => '',
            'descripcion_emergencia' => '',
        ]);
    }
}
