<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crea la estructura de la tabla members (integrantes del núcleo familiar)
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {

            // Identificador único del integrante
            $table->id();

            // Nombres del integrante
            $table->string('names', 50);

            // Apellidos del integrante
            $table->string('last_names', 50);

            // Fecha de nacimiento para cálculo de edad
            $table->date('birth_date');

            // Relación con el grupo sanguíneo
            $table->foreignId('blood_group_id')->constrained();

            // Relación con el tipo de documento
            $table->foreignId('document_type_id')->constrained();

            // Número del documento de identidad
            $table->string('document_number', 20);

            // Relación con la nacionalidad del integrante
            $table->foreignId('nationality_id')->constrained();

            // Relación con el género del integrante
            $table->foreignId('gender_id')->constrained();

            // Relación con el parentesco dentro del grupo familiar
            $table->foreignId('kinship_id')->constrained();

            // Entidad prestadora de salud del integrante
            $table->foreignId('eps_id')->constrained();

            // Teléfono de contacto del integrante
            $table->string('phone', 10);

            // Observación o novedad relevante del integrante
            $table->string('novelty', 255)->nullable();;

            // Fechas de creación y actualización del registro
            $table->timestamps();
        });
    }

    // Elimina la tabla members en caso de rollback
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
