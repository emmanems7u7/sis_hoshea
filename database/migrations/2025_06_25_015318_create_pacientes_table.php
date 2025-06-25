<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();

            // Contacto
            $table->string('telefono_fijo', 20)->nullable();
            $table->string('telefono_movil', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('direccion', 255)->nullable();

            // Documentos e identificación
            $table->string('tipo_documento', 50)->nullable()->comment('Tipo de documento, ej: CI, Pasaporte');
            $table->string('numero_documento', 50)->nullable()->unique();

            // Información adicional
            $table->string('pais', 50)->nullable()->comment('Código foráneo a catálogo de países');
            $table->string('ciudad', 50)->nullable()->comment('Código foráneo a catálogo de ciudades');

            // Estado y control
            $table->boolean('activo')->default(true)->comment('Estado activo/inactivo del paciente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
