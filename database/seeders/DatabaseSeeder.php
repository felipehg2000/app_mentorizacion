<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Mentor;
use App\Models\Study_room;
use App\Models\Task;
use App\Models\Study_room_acces;
use App\Models\Friend_request;
use App\Models\Synchronous_message;

class DatabaseSeeder extends Seeder
{
    private $estudiantes_procesados = [];

    /**
     * Seed the application's database.
     */
    public function run(): void {
        $users = User::factory(50)->create();

        foreach ($users as $user) {
            if ($user->user_type === 1) {
                // Es un estudiante
                Student::factory()->forStudents($user->id)->create();
            } elseif ($user->user_type === 2) {
                // Es un mentor
                Mentor    ::factory()->forMentors($user->id)->create();
                Study_room::factory()->forMentors($user->id)->create();
                Task      ::factory()->count(rand(1, 10))->forMentors($user->id)->create();
            }
        }

        foreach ($users as $mentor){
            if ($mentor->user_type == 2){
                $limite = rand(1, 5);
                $this->RellenarSalaYSolicitudesMentor($users, $mentor, $limite);
            }
        }

        $this->RellenarChats();
    }

    private function RellenarChats(){
        $studyRoomAccesses = Study_room_acces::where('LOGIC_CANCEL', 0)->get();

        foreach($studyRoomAccesses as $chat){
            $student_id = $chat->STUDENT_ID;
            $mentor_id  = $chat->STUDY_ROOM_ID;
            Synchronous_message::factory()->count(rand(0, 50))->withParams($student_id, $mentor_id)->create();
        }
    }

    private function RellenarSalaYSolicitudesMentor($users, $mentor, $limite){
        $contador = 0;
        foreach ($users as $student){
            if ($limite == $contador){
                return;
            }

            if ($student->user_type == 1){
                if ($mentor->study_area == $student->study_area) {
                    if (!$this->EstudianteProcesado($student->id, $this->estudiantes_procesados)) {
                        $aceptado = rand(1, 2); //Si es 1 el usuario está aceptado en la sala de estudios, si es dos el estudiante solo ha solicitado
                        if ($aceptado == 1){
                            Study_room_acces::factory()->withParams($student->id, $mentor->id         )->create();
                            Friend_request  ::factory()->withParams($student->id, $mentor->id, 2, 1, 0)->create();
                            $this->estudiantes_procesados[] = $student->id;
                            $contador++;
                        }elseif ($aceptado == 2) {
                            Friend_request::factory()->withParams($student->id, $mentor->id, 1, 0, 1)->create();
                            $this->estudiantes_procesados[] = $student->id;
                            $contador++;
                        }
                    }
                }
            }
        }
    }

    private function EstudianteProcesado($id_estudiante, $param_lista) {
        $length = count($param_lista);

        for ($it = 0; $it < $length; $it++){
            if ($param_lista[$it] == $id_estudiante){
                return true;
            }
        }

        return false;
    }
}
