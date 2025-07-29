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
        Schema::create('paciente_antecedente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');

            $table->string('antecedente', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('antecedente')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('familiar', 50)->nullable()->comment('Clave foránea a catalogos.catalogo_codigo');
            $table->foreign('familiar')->references('catalogo_codigo')->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
