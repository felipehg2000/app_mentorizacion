<?php
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-14 20:19:30
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2023-03-14 20:32:06
 * @Description: Migración completa para la base de datos de la primera versión de la aplicación mentoring, en la primera modificación añadiremos
 *               los datos respectivos al usuario.
 */

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
            $table->id           (                   );
            $table->string       ('nombre'       , 30);
            $table->string       ('apellidos'    , 90)->nullable();
            $table->string       ('email'            )->unique  ();
            $table->string       ('usuario'      , 30)->unique  ();
            $table->text         ('clave'            );
            $table->boolean      ('tipo_usuario'     );
            $table->integer      ('campo_estudio'    );
            $table->text         ('descripcion'      )->nullable();
            $table->rememberToken(                   );//en caso de que el usuario decida tener la sesión abierta se guardará un token
            $table->timestamps   (                   );
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
