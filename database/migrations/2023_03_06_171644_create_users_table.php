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

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void{
        /**
         * TABLA USERS:
         * ============
         *
         * Tabla que guarda los datos más genéricos de los usuarios
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id           (                 );
            $table->string       ('name'       , 30);
            $table->string       ('surname'    , 90)->nullable();
            $table->string       ('email'          )->unique  ();
            $table->timestamp    ('email_verified_at')->nullable();
            $table->string       ('user'       , 30)->unique  ();
            $table->text         ('password'       );
            $table->boolean      ('user_type'      );
            $table->integer      ('study_area'     );
            $table->text         ('description'    )->nullable();
            $table->binary       ('image'          )->default($this->imageToBinary('photos/default_user_image.png'));
            $table->rememberToken(                 );//en caso de que el usuario decida tener la sesión abierta se guardará un token
            $table->timestamps   (                 );
        });

        /**
         * TABLA STUDENTS
         * ==============
         *
         * Datos específicos de los usuarios de tipo estudiante. Herenciada de la tabla usuarios, tienen la misma clave.
         */
        Schema::create('students', function (Blueprint $table){
            $table->integer('user_id')   ->primary ();
            $table->string ('career')    ->nullable();
            $table->year   ('first_year')->nullable();
            $table->integer('duration')  ->nullable();
        });

        /**
         * TABLA MENTROS
         * =============
         *
         * Datos específicos de los usuarios de tipo mentor. Herencia de la tabla usuarios, tienen la misma clave.
         */
        Schema::create('mentors', function (Blueprint $table){
            $table->integer('user_id')->primary ();
            $table->string ('company')->nullable();
            $table->string ('job')    ->nullable();
        });

        /**
         * TABLA INHERITANCE_USERS:
         * ========================
         *
         * Herencia de los usuarios almacenará la clave del usuario y el nombre de la tabla a la que habrá que acceder para ver el resto de datos.
         */
        Schema::create('inheritance_users', function (Blueprint $table){
            $table->integer('user_id')   ->primary();
            $table->string ('table_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('users'            );
        Schema::dropIfExists('students'         );
        Schema::dropIfExists('mentors'          );
        Schema::dropIfExists('inheritance_users');
    }

    /**
     * Función para subir los datos de las imagenes en binario
     */
    private function imageToBinary(String $path){
        $image_path = public_path($path);
        $image_data = file_get_contents($image_path);
        return base64_encode($image_data);
    }

};
