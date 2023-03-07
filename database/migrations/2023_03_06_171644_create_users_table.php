<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30);
            $table->string('apellidos');
            $table->string('email');
            $table->string('usuario');
            $table->string('clave');
            $table->boolean('mentor');
            $table->string('campo_estudio');
            $table->rememberToken();//en caso de que el usuario decida tener la sesión abierta se guardará un token
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
