<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Study_room_acces;
use App\Models\User;

class MentorsController extends Controller{
    //--------------------------------------------------------------------------------------------------
    /**
     * INDEX:
     * ======
     * Función encargada de mostrar la vista al mentor, redirigirá automáticamente a la ventana de su sala.
     * TO DO: hacer la dirección automática para que se modifique según el mentor.
     *        hacer el tratamiento de la foto para el perfil
     *        hacer las especificaciones de la vista
     */
    public function index(){
        $this->BinaryToPhoto(Auth::user()->IMAGE);
        return view('mentors.index');
    }
    //--------------------------------------------------------------------------------------------------
    /**
     *  FRIENDSHIP
     *  ==========
     * Función get : devuelve la ruta de la vista que querramos mostrar
     * Función post: Se guarda en la base de datos que el estado es aceptado o se borra el registro en caso de que sea denegada la solicitud
     */
    public function friendship(){
        $result_user = DB::table('USERS')
                         ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.STUDENT_ID', '=', 'USERS.ID')
                         ->where('FRIEND_REQUESTS.MENTOR_ID', '=', Auth::user()->id)
                         ->where('FRIEND_REQUESTS.STATUS', '=', 1)
                         ->select('USERS.*')
                         ->get();

        /*$result_student = Student::where('id', $result_user->id)
                                 ->get();
        */
        foreach($result_user as $user){
            $this->convertToPhoto($user->IMAGE, $user->USER);
        }

        return view('mentors.friendship', compact('result_user'));
    }
    public function friendship_store (Request $request){
        $mentor_id  = Auth::user()->id;
        $student_id = DB::table('USERS')->select('ID')->where('user', $request->user_user)->get()->first()->ID;
        if ($request->respuesta == "ACEPTAR"){
            //Aceptar petición
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->update(['STATUS' => 2]);

              $this->CreateStudyRoomAcces($student_id, $mentor_id);

        }else if ($request->respuesta == "DENEGAR"){
            //Borrar petición
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', '=', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->delete();
        }

        return back();
    }
    //--------------------------------------------------------------------------------------------------
    /**
     *  ACTUAL FREIENDS
     *  ===============
     * Función get : devuelve la vista que queremos ver (vista con todos los usuarios que se encuentran relacionados con el auth en la tabla FRIENDSHIP_REQUEST)
     * Función post: ¿No estoy seguro de si se necesita, para dejar de seguir a la gente por ejemplo?
     */
    public function actual_friends(){
        //Buscar los usuarios relacionados con nosotros
        $result_users = DB::table('USERS')
                          ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.STUDENT_ID', '=', 'USERS.ID')
                          ->where('FRIEND_REQUESTS.MENTOR_ID', '=', Auth::user()->id)
                          ->where('FRIEND_REQUESTS.STATUS', '=', 2)
                          ->select('USERS.*')
                          ->get();
        return view('mentors.actual_friends', compact('result_users'));
    }
    public function actual_friends_store(Request $request){
        $student_id = DB::table('USERS')->select('ID')->where('user', $request->user_user)->get()->first()->ID;
        $mentor_id  = Auth::user()->id;
        DB::table('FRIEND_REQUESTS')
          ->where('STUDENT_ID', '=', $student_id)
          ->where('MENTOR_ID' , '=', $mentor_id)
          ->delete();

        return back();
    }
    //--------------------------------------------------------------------------------------------------
    /**
     * FUNCIONES AUXILIARES:
     * =====================
     * Distintas funcionalidades para simplificar el código
     */
    private function BinaryToPhoto(String $image_db){
        $image_binary = $image_db;
        $image_data   = base64_decode($image_binary);
        $destination_path = public_path('photos\my_image.png');
        file_put_contents($destination_path, $image_data);
    }

    private function convertToPhoto($blopPhoto, $user){
        //Creamos la ruta y el archivo
        $path             = "photos/users/User" . $user. ".png";
        $destination_path = public_path($path);

        touch($destination_path);

        //Convertimos el campo blop en una imagen
        $image_binary = $blopPhoto;
        $image_data   = base64_decode($image_binary);

        //Guardamos los datos de la imagen en el file creado
        if (file_exists($destination_path)){
            file_put_contents($destination_path, $image_data);
        }
    }

    private function CreateStudyRoomAcces($param_student_id, $param_mentor_id) {
        $study_room_id = DB::table ('STUDY_ROOMS')
                           ->where ('MENTOR_ID', '=', $param_mentor_id)
                           ->select('id')
                           ->first ();

        $new_node = new Study_room_acces();
        $new_node->student_id    = $param_student_id;
        $new_node->study_room_id = $study_room_id;

        $new_node->save();
    }
}
