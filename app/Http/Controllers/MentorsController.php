<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        return view('mentors.friendship');
    }
    public function friendship_store (Request $request){

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
}
