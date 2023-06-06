<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Student;
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
        $result_user = User::join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.STUDENT_ID', '=', 'USERS.id')
                           ->select('users.*')
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
            //Modificar el estado de la solicitud de amistad
            echo 'ACEPTADA';
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->update(['STATUS' => 2]);

        }else if ($request->respuesta == "DENEGAR"){
            //Borrar la solicitud de amistad
            echo 'DENEGADA';
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', '=', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->delete();
        }
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
    //--------------------------------------------------------------------------------------------------
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
}
