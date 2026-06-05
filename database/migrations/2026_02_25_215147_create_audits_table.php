<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();

            // Usuario que ejecutó la acción
            $table->string('user_name');
            $table->string('rol_name');

            // Fecha y hora del evento
            $table->dateTime('date_time');

            // Acción realizada (CREATE, UPDATE, DELETE, etc.)
            $table->string('action_execute');

            // Estados
            $table->string('status_old')->nullable();
            $table->string('status_new')->nullable();

            //Datos capturados
            $table->text('data_old')->nullable();
            $table->text('data_new')->nullable();
            $table->text('subData_old')->nullable();
            $table->text('subData_new')->nullable();

            // Relación polimórfica
            $table->morphs('historiable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
