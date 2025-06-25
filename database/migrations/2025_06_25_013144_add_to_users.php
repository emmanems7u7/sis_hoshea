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
        Schema::table('users', function (Blueprint $table) {

            $table->date('fecha_nacimiento')->nullable()->comment('Fecha de nacimiento');
            $table->string('genero')->nullable()->comment('Género o sexo (M/F/O)');
            $table->string('documento_identidad')->nullable()->comment('Número de documento de identidad');

            $table->string('pais', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('pais')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->string('ciudad', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('ciudad')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
