<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paciente;
use Illuminate\Support\Str;

use Faker\Factory as Faker;
class PacientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES'); // Español para nombres latinoamericanos

        for ($i = 0; $i < 50; $i++) {
            Paciente::create([
                'nombres' => $faker->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'fecha_nacimiento' => $faker->date($format = 'Y-m-d', $max = '-18 years'),
                'genero' => $faker->randomElement(['M', 'F']),
                'telefono_fijo' => $faker->phoneNumber,
                'telefono_movil' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'direccion' => $faker->address,
                'tipo_documento' => $faker->randomElement(['CI', 'Pasaporte']),
                'numero_documento' => $faker->unique()->numberBetween(10000000, 99999999),
                'pais' => 'P-001',
                'departamento' => 'D-001',
                'ciudad' => 'C-001',
                'activo' => 1,
            ]);
        }
    }
}
