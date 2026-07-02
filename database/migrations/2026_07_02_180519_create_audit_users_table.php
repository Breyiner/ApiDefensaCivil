<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_users', function (Blueprint $table) {
            $table->id();

            $table->string('user_name');
            $table->string('rol_name');
            $table->dateTime('date_time');
            $table->string('action_execute');

            $table->string('status_old')->nullable();
            $table->string('status_new')->nullable();

            $table->string('userName_old')->nullable();
            $table->string('userName_new')->nullable();

            $table->string('lastName_old')->nullable();
            $table->string('lastName_new')->nullable();

            $table->string('userRol_old')->nullable();
            $table->string('userRol_new')->nullable();

            $table->string('documentType_old')->nullable();
            $table->string('documentType_new')->nullable();

            $table->string('numberDocument_old')->nullable();
            $table->string('numberDocument_new')->nullable();

            $table->date('birthDate_old')->nullable();
            $table->date('birthDate_new')->nullable();

            $table->string('gender_old')->nullable();
            $table->string('gender_new')->nullable();

            $table->string('sectional_old')->nullable();
            $table->string('sectional_new')->nullable();

            $table->string('organization_old')->nullable();
            $table->string('organization_new')->nullable();

            $table->morphs('historiable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_users');
    }
};