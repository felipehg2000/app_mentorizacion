<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Friend_request;

class StudentsController extends Controller{
    //--------------------------------------------------------------------------------------------------
    /**
     * INDEX
     * =====
     * Pagina principal para los usuarios, inicialmente será la página que vean cuando no estén dados de alta en ningún aula, se mostrará el listado de mentores
     * con la condición de que estos mentores pertenezcan a su mismo area de estudios
     */
    public function index(){
        $this->BinaryToPhoto(Auth::user()->IMAGE);
        return view('students.index');
    }
    //--------------------------------------------------------------------------------------------------
    /**
     * FRIENDSHIP REDIRECTION
     * ======================
     * Guarda en una variable todos los mentores que sean del mismo campo de estudio que el estudiante y muestran la vista del estudiante de búsqueda de amigos mostrando
     * esos usuarios.
     *
     * TO DO: Tengo que quitar de esta vista los que ya aparezcan en la tabla de solicitudes de amistad.
     */
    public function friendship(){
        $user_type = 2;
        $users     = User::where('STUDY_AREA', Auth::user()->STUDY_AREA)
                         ->where('USER_TYPE' , $user_type              )
                         ->get();

        foreach ($users as $user){
            $this->convertToPhoto($user->IMAGE, $user->USER);
        }

        return view ('students.friendship', compact('users'));
    }
//----------------------------------------- ---------------------------------------------------------
    /**
     * FRIENDSHIP_STORE:
     * =================
     *
     * Recibe el nombre del usaurio con el que quiere contactar y se encarga de dar de alta en la base de datos la solicitud de amisrtad.
     */
    public function friendship_store(Request $request){
        /**Dar de alta la entrada para que los usuarios y los mentroes queden conectados. */
        $student_id = Auth::user()->id;
        $mentor_id  = DB::table('USERS')->select('ID')->where('user', $request->user_user)->get()->first()->ID;

        $resultado = Friend_request::where('MENTOR_ID' , $mentor_id)
                                   ->where('STUDENT_ID', $student_id)
                                   ->first();

        if ($resultado == NULL)
            {
            $friendRequest = new Friend_request();
            $friendRequest->mentor_id  = $mentor_id ;
            $friendRequest->student_id = $student_id;
            $friendRequest->status     = 1          ;

            $friendRequest->save();
            }
        else
            {
            return redirect()->back()->withErrors(['error' => $request->user_user]);
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
                          ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.MENTOR_ID', '=', 'USERS.ID')
                          ->where('FRIEND_REQUESTS.STUDENT_ID', '=', Auth::user()->id)
                          ->where('FRIEND_REQUESTS.STATUS', '=', 2)
                          ->select('USERS.*')
                          ->get();
        return view('students.actual_friends', compact('result_users'));
    }
    public function actual_friends_store(Request $request){
        $student_id = Auth::user()->id;
        $mentor_id  = DB::table('USERS')->select('ID')->where('USER', $request->user_user)->get()->first()->ID;

        DB::table('FRIEND_REQUESTS')
          ->where('STUDENT_ID', '=', $student_id)
          ->where('MENTOR_ID' , '=', $mentor_id )
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

//EVENTS Y LISTENERS BUSCAR INTERNET.
//BOOSTRAP.STUDIO O ALGO ASÍN

