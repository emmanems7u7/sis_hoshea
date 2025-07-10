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
        Schema::create('planes_tratamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained();
            $table->string('tipo', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('tipo')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_tratamientos');
    }
};
