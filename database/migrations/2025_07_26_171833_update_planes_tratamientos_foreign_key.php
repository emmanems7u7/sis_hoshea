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
        Schema::table('planes_tratamientos', function (Blueprint $table) {
            $table->dropForeign(['cita_id']);
            $table->foreign('cita_id')->references('id')->on('citas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planes_tratamientos', function (Blueprint $table) {
            $table->dropForeign(['cita_id']);
            $table->foreign('cita_id')->references('id')->on('citas');
        });
    }
};
