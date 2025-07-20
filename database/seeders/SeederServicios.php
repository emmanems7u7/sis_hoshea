<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;
class SeederServicios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Consulta General', 'descripcion' => 'Evaluación médica general', 'precio' => 50.00],
            ['nombre' => 'Consulta Pediátrica', 'descripcion' => 'Atención médica para niños', 'precio' => 60.00],
            ['nombre' => 'Consulta Ginecológica', 'descripcion' => 'Atención especializada en ginecología', 'precio' => 70.00],
            ['nombre' => 'Consulta Cardiológica', 'descripcion' => 'Evaluación del sistema cardiovascular', 'precio' => 80.00],
            ['nombre' => 'Consulta Neurológica', 'descripcion' => 'Evaluación neurológica especializada', 'precio' => 90.00],
            ['nombre' => 'Consulta Dermatológica', 'descripcion' => 'Atención para problemas de piel', 'precio' => 65.00],
            ['nombre' => 'Consulta Oftalmológica', 'descripcion' => 'Examen de la vista y ojos', 'precio' => 75.00],
            ['nombre' => 'Consulta Otorrinolaringológica', 'descripcion' => 'Atención de oído, nariz y garganta', 'precio' => 70.00],
            ['nombre' => 'Consulta Endocrinológica', 'descripcion' => 'Tratamiento hormonal y metabólico', 'precio' => 85.00],
            ['nombre' => 'Consulta Psiquiátrica', 'descripcion' => 'Evaluación y tratamiento psiquiátrico', 'precio' => 90.00],
            ['nombre' => 'Consulta Nutricional', 'descripcion' => 'Asesoramiento nutricional y dietético', 'precio' => 50.00],
            ['nombre' => 'Consulta Fisioterapia', 'descripcion' => 'Rehabilitación y terapia física', 'precio' => 60.00],
            ['nombre' => 'Electrocardiograma (ECG)', 'descripcion' => 'Registro de actividad eléctrica del corazón', 'precio' => 40.00],
            ['nombre' => 'Ecografía Abdominal', 'descripcion' => 'Ultrasonido del abdomen', 'precio' => 100.00],
            ['nombre' => 'Radiografía de Tórax', 'descripcion' => 'Imagen radiológica del tórax', 'precio' => 80.00],
            ['nombre' => 'Análisis de Sangre Completo', 'descripcion' => 'Examen completo de sangre', 'precio' => 120.00],
            ['nombre' => 'Prueba de Orina', 'descripcion' => 'Análisis de orina', 'precio' => 30.00],
            ['nombre' => 'Prueba de Glucosa', 'descripcion' => 'Medición de azúcar en sangre', 'precio' => 25.00],
            ['nombre' => 'Vacunación', 'descripcion' => 'Aplicación de vacunas', 'precio' => 40.00],
            ['nombre' => 'Inyección Intramuscular', 'descripcion' => 'Aplicación de inyección', 'precio' => 20.00],
            ['nombre' => 'Curación de Heridas', 'descripcion' => 'Tratamiento de heridas', 'precio' => 30.00],
            ['nombre' => 'Electroencefalograma (EEG)', 'descripcion' => 'Registro de actividad cerebral', 'precio' => 150.00],
            ['nombre' => 'Prueba de Esfuerzo', 'descripcion' => 'Evaluación del corazón bajo estrés', 'precio' => 200.00],
            ['nombre' => 'Consulta Odontológica', 'descripcion' => 'Revisión dental general', 'precio' => 55.00],
            ['nombre' => 'Limpieza Dental', 'descripcion' => 'Higiene y limpieza bucal', 'precio' => 70.00],
            ['nombre' => 'Consulta Psiquiátrica Infantil', 'descripcion' => 'Evaluación psiquiátrica para niños', 'precio' => 85.00],
            ['nombre' => 'Terapia Ocupacional', 'descripcion' => 'Terapia para mejorar habilidades diarias', 'precio' => 65.00],
            ['nombre' => 'Consulta de Medicina Familiar', 'descripcion' => 'Atención integral familiar', 'precio' => 50.00],
            ['nombre' => 'Prueba de VIH', 'descripcion' => 'Test rápido para VIH', 'precio' => 45.00],
            ['nombre' => 'Consulta de Medicina Interna', 'descripcion' => 'Evaluación de enfermedades internas', 'precio' => 75.00],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio + ['activo' => true]);
        }
    }
}
