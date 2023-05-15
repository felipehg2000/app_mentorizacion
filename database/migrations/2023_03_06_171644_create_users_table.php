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
     *
     * Cración y modificación de las distintas tablas de la base de datos
     */
    public function up(): void{
        /**
         * TABLA USERS:
         * ============
         *
         * Tabla que guarda los datos más genéricos de los usuarios
         */
        Schema::create('USERS', function (Blueprint $table) {
            $table->id           (                      );
            $table->string       ('NAME'       , 30     );
            $table->string       ('SURNAME'    , 90     )->nullable();
            $table->string       ('EMAIL'               )->unique  ();
            $table->timestamp    ('EMAIL_VERIFICATE_AT' )->nullable();
            $table->string       ('USER'       , 30     )->unique  ();
            $table->text         ('PASSWORD'            );
            $table->boolean      ('USER_TYPE'           );
            $table->integer      ('STUDY_AREA'          );
            $table->text         ('DESCRIPTION'         )->nullable();
            $table->binary       ('IMAGE'               )->default($this->imageToBinary('photos/default_user_image.png'));
            $table->rememberToken(                      );//en caso de que el usuario decida tener la sesión abierta se guardará un token
            $table->timestamps   (                      );
        });

        /**
         * TABLA STUDENTS
         * ==============
         *
         * Datos específicos de los usuarios de tipo estudiante. Herenciada de la tabla usuarios, tienen la misma clave.
         */
        Schema::create('STUDENTS', function (Blueprint $table){
            $table->unsignedBigInteger  ('USER_ID'   )->primary ();
            $table->string              ('CAREER'    )->nullable();
            $table->year                ('FIRST_YEAR')->nullable();
            $table->integer             ('DURATION'  )->nullable();
            $table->timestamps          ();

            $table->foreign('USER_ID')->references('ID')->on('USERS')->onDelete('CASCADE');
        });

        /**
         * TABLA MENTROS
         * =============
         *
         * Datos específicos de los usuarios de tipo mentor. Herencia de la tabla usuarios, tienen la misma clave.
         */
        Schema::create('MENTORS', function (Blueprint $table){
            $table->unsignedBigInteger  ('USER_ID')->primary ();
            $table->string              ('COMPANY')->nullable();
            $table->string              ('JOB'    )->nullable();
            $table->timestamps          ();

            $table->foreign('USER_ID')->references('ID')->on('USERS')->onDelete('CASCADE');
        });

        /**
         * TABLA INHERITANCE_USERS:
         * ========================
         *
         * Herencia de los usuarios almacenará la clave del usuario y el nombre de la tabla a la que habrá que acceder para ver el resto de datos.
         */
        Schema::create('INHERITANCE_USERS', function (Blueprint $table){
            $table->unsignedBigInteger  ('USER_ID'   )->primary();
            $table->string              ('TABLE_NAME');
            $table->timestamps          ();

            $table->foreign('USER_ID')->references('ID')->on('USERS')->onDelete('CASCADE');
        });

        /**
         * TABLA STUDY_ROOMS
         * =================
         * Tabla asociada al mentor que contiene las características especiales de su sala.
         */
        Schema::create('STUDY_ROOMS', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('MENTOR_ID');
            $table->string              ('COLOR'    )->nullable();
            $table->timestamps          ();

            $table->foreign('MENTOR_ID')->references('USER_ID')->on('MENTORS')->onDelete('CASCADE');
        });

        /**
         * TABLA STUDY_ROOM_ACCES:
         * =======================
         * Se almacena la información de a que sala tiene acceso cada estudiante.
         */
        Schema::create('STUDY_ROOM_ACCES', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('STUDENT_ID'   );
            $table->unsignedBigInteger  ("STUDY_ROOM_ID");
            $table->timestamps          ();

            $table->foreign('STUDENT_ID'   )->references('USER_ID')->on('STUDENTS'   )->onDelete('CASCADE');
            $table->foreign('STUDY_ROOM_ID')->references('ID'     )->on('STUDY_ROOMS')->onDelete('CASCADE');
        });

        /**
         * TABLA FRIEND_REQUEST:
         * =====================
         * Almacena la información de las solicitudes de amistad enviadas por los estudiantes a los mentores. Y si estas han sido aceptadas, rechazadas o no tienen
         * respuesta aún.
         */
        Schema::create('FRIEND_REQUESTS', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('MENTOR_ID' );
            $table->unsignedBigInteger  ('STUDENT_ID');
            $table->integer             ('STATUS'    )->nullable();
            $table->timestamps          ();

            $table->foreign('MENTOR_ID' )->references('USER_ID')->on('MENTORS' )->onDelete('CASCADE');
            $table->foreign('STUDENT_ID')->references('USER_ID')->on('STUDENTS')->onDelete('CASCADE');
        });

        /**
         * TABLA SYNCHRONOUS_MESSAGES:
         * ===========================
         * Almacena los mensajes de los chats privados entre usuarios.
         */
        Schema::create('SYNCHRONOUS_MESSAGES', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('STUDY_ROOM_ID');
            $table->integer             ('SENDER'       ); /*1.- Mensaje del mentor, 2.- Mensaje del estudiante*/
            $table->text                ('MESSAGE'      );
            $table->timestamps          ();

            $table->foreign('STUDY_ROOM_ID')->references('ID')->on('STUDY_ROOMS')->onDelete('CASCADE');
        });

        /**
         * TABLA ASYNCHRONOUS_MESSAGES:
         * ============================
         * Almacena toda la información del tablón de anuncos de la aplicación, con las tareas subidas por los estudiantes y las respuestas dadas por los mentores.
         */
        Schema::create('ASYNCHRONOUS_MESSAGES', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('STUDY_ROOM_ID'    );
            $table->integer             ('SENDER'           );/*Tengo que ver como hacerlo pero creo que tengo que poner una referencia al id del estudiante que lo sube para poder identificarlo*/
            $table->text                ('MESSAGE'          )->nullable();
            $table->binary              ('DOCUMENT'         )->nullable();
            $table->integer             ('TYPE_OF_DOCUMENT' )->nullable();
            $table->timestamps          ();

            $table->foreign('STUDY_ROOM_ID')->references('ID')->on('STUDY_ROOMS')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Borra la migración a la base de datos completamente.
     */
    public function down(): void{
        Schema::dropIfExists('USERS');
        Schema::dropIfExists('STUDENTS');
        Schema::dropIfExists('MENTORS'              );
        Schema::dropIfExists('INHERITANCE_USERS'    );
        Schema::dropIfExists('STUDY_ROOMS'          );
        Schema::dropIfExists('STUDY_ROOM_ACCES'     );
        Schema::dropIfExists('FRIEND_REQUESTS'      );
        Schema::dropIfExists('SYNCHRONOUS_MESSAGES' );
        Schema::dropIfExists('ASYNCHRONOUS_MESSAGES');
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
