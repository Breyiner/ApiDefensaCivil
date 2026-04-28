<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla 'housing_infos' para adjuntar archivos a los planes familiares.
     */
    public function up(): void
    {
        Schema::create('housing_infos', function (Blueprint $table) {
            // ID único de la información de vivienda
            $table->id();

            /**
             * RELACIÓN: Se vincula directamente con un plan familiar.
             * Esto permite que un plan tenga múltiples archivos adjuntos (Relación 1 a Muchos).
             */
            $table->foreignId('family_plan_id')->constrained();

            /**
             * PATH: Almacena la ruta del archivo en el servidor o en la nube (S3, Google Cloud).
             * Ejemplo: 'uploads/housing/documento_vivienda_123.pdf'
             */
            $table->string('path');

            // $table->foreign('housing_info_type_id')->references('id')->on('housing_info_types');
            $table->foreignId('housing_info_type_id')->constrained('housing_info_types');

            // Auditoría: created_at y updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_infos');
    }
};