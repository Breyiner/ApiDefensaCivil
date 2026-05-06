<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Crea la tabla 'family_plans', que integra la información geográfica y social del plan familiar.
     */
    public function up(): void
    {
        Schema::create('family_plans', function (Blueprint $table) {
            // ID único del plan familiar
            $table->id();

            // Apellidos que identifican al grupo familiar (ej: 'Familia Rodríguez Pérez')
            $table->string('last_names', 255);

            //Tipo de familia (relación con family_types)
            $table->foreignId('family_type_id')->constrained('family_types');

            // --- RELACIONES OBLIGATORIAS ---
            // Zona geográfica (Norte, Sur, etc.)
            $table->foreignId('zone_id')->constrained();
            // Ciudad donde se ejecuta el plan
            $table->foreignId('city_id')->constrained();
            
            // Campo booleano para autorizaciones legales (1 por defecto)
            $table->boolean('authorization')->default(1);

            // Seccional y Estado del plan (ej: 'Vigente', 'En espera')
            $table->foreignId('sectional_id')->constrained();
            $table->foreignId('status_plan_id')->constrained();

            // --- CAMPOS OPCIONALES (nullable) ---
            // Dirección física de la vivienda
            $table->string('address', 255)->nullable();

            /**
             * El sector puede ser referenciado por ID (relación foránea) 
             * o guardado por nombre manualmente en 'sector_name'.
             */
            $table->foreignId('sector_id')->nullable()->constrained();
            $table->string('sector_name', 50)->nullable();

            // Teléfono fijo (limitado a 10 dígitos)
            $table->string('landline_phone', 10)->nullable();

            // Calidad de la vivienda (relación con housing_qualities)
            $table->foreignId('housing_quality_id')->nullable()->constrained();

            // Campo de texto largo para coordenadas o datos de GPS
            $table->foreignId('user_id')->constrained();
            $table->text('comentary')->nullable();

            // Auditoría
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_plans');
    }
};