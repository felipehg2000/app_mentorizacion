<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller{
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


    public function friendship_redirection(){
        $user_type = 2;
        $users     = User::where('STUDY_AREA', Auth::user()->STUDY_AREA)
                         ->where('USER_TYPE' , $user_type              )
                         ->get();

        foreach ($users as $user){
            $this->convertToPhoto($user->IMAGE, $user->USER);
        }

        return view ('students.friendship', compact('users'));
    }
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
