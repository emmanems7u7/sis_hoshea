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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained()->onDelete('cascade');
            $table->string('cod_diagnostico', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('cod_diagnostico')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('fecha_diagnostico');
            $table->string('estado')->default('activo');
            $table->text('criterio_clinico')->nullable();
            $table->text('evolucion_diagnostico')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
