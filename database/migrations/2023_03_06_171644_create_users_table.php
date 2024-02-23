<?php
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-14 20:19:30
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-02-23 20:35:08
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
            $table->unsignedBigInteger  ('MENTOR_ID')->primary();
            $table->string              ('COLOR'    )->nullable();
            $table->timestamps          ();

            $table->foreign('MENTOR_ID')->references('USER_ID')->on('MENTORS')->onDelete('CASCADE');
        });

        /**
         * TABLA STUDY_ROOM_ACCES:
         * =======================
         * Se almacena la información de a que sala tiene acceso cada estudiante.
         */
        Schema::create('STUDY_ROOM_ACCESS', function(Blueprint $table){
            $table->unsignedBigInteger  ('STUDENT_ID'   )->primary();
            $table->unsignedBigInteger  ('STUDY_ROOM_ID');
            $table->boolean             ('LOGIC_CANCEL' );
            $table->timestamps          ();

            $table->foreign('STUDENT_ID'   )->references('USER_ID'  )->on('STUDENTS'   )->onDelete('CASCADE');
            $table->foreign('STUDY_ROOM_ID')->references('MENTOR_ID')->on('STUDY_ROOMS')->onDelete('CASCADE');
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
            $table->unsignedBigInteger  ('STUDY_ROOM_ACCES_ID');
            $table->integer             ('SENDER'       ); /*1.- Mensaje del mentor, 2.- Mensaje del estudiante*/
            $table->text                ('MESSAGE'      );
            $table->timestamps          ();

            $table->foreign('STUDY_ROOM_ID'      )->references('MENTOR_ID')->on('STUDY_ROOMS'       )->onDelete('CASCADE');
            $table->foreign('STUDY_ROOM_ACCES_ID')->references('STUDENT_ID')->on('STUDY_ROOM_ACCESS')->onDelete('CASCADE');
        });

        /**
         * TASKS:
         * ======
         * Almacena toda la información de las tareas creadas por el mentor en la sala de estudio
         */
        Schema::create('TASKS', function(Blueprint $table){
            $table->id                  ();
            $table->unsignedBigInteger  ('STUDY_ROOM_ID');
            $table->string              ('TASK_TITLE'   );
            $table->text                ('DESCRIPTION'  );
            $table->boolean             ('STATEMENT'    );
            $table->dateTime            ('LAST_DAY'     );
            $table->timestamps          ();

            $table->foreign('STUDY_ROOM_ID')->references('MENTOR_ID')->on('STUDY_ROOMS')->onDelete('CASCADE');
        });

        /**
         * SOLUTIONS:
         * ==========
         * Almacena la respuesta de cada usuario de la sala de estudio a la tarea que se ha creado
         * Info: No me permite poner dos atributos como claves primarias, por lo que pondremos uno como primaria y otro como unica
         */
        Schema::create('ANSWERS', function(Blueprint $table){
            $table->unsignedBigInteger('TASK_ID'            );
            $table->unsignedBigInteger('STUDY_ROOM_ACCES_ID');
            $table->text              ('TYPE_OF_DOCUMENT'   );
            $table->binary            ('DOCUMENT'           );
            $table->timestamps        ();

            $table->primary('TASK_ID', 'STUDY_ROOM_ACCES_ID');

            $table->foreign('TASK_ID'            )->references('ID'        )->on('TASKS'             )->onDelete('CASCADE');
            $table->foreign('STUDY_ROOM_ACCES_ID')->references('STUDENT_ID')->on('STUDY_ROOM_ACCESS')->onDelete('CASCADE');
        });

        /**
         * TUTORING:
         * =========
         * Almacena la fecha para la que se propone una tutoría y si está ha sido aceptada o no por el mentor
         */
        Schema::create('TUTORING', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('STUDY_ROOM_ID'       );
            $table->unsignedBigInteger('STUDY_ROOM_ACCES_ID' );
            $table->dateTime          ('DATE'                );
            $table->boolean           ('STATUS'              )->nullable(); //0 DENEGADA, 1 ACEPTADA
            $table->timestamps        ();

            $table->foreign('STUDY_ROOM_ID'      )->references('MENTOR_ID' )->on('STUDY_ROOMS'       )->onDelete('CASCADE');
            $table->foreign('STUDY_ROOM_ACCES_ID')->references('STUDENT_ID')->on('STUDY_ROOM_ACCESS')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * Borra la migración a la base de datos completamente.
     */
    public function down(): void{
        Schema::dropIfExists('FRIEND_REQUESTS'      );
        Schema::dropIfExists('INHERITANCE_USERS'    );
        Schema::dropIfExists('TUTORING'             );
        Schema::dropIfExists('ANSWERS'              );
        Schema::dropIfExists('TASKS'                );
        Schema::dropIfExists('SYNCHRONOUS_MESSAGES' );
        Schema::dropIfExists('STUDY_ROOM_ACCES'     );
        Schema::dropIfExists('STUDY_ROOMS'          );
        Schema::dropIfExists('MENTORS'              );
        Schema::dropIfExists('STUDENTS'             );
        Schema::dropIfExists('USERS'                );
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
