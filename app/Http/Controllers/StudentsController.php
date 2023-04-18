<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StudentsController extends Controller{
    /**
     * INDEX
     * =====
     * Pagina principal para los usuarios, inicialmente será la página que vean cuando no estén dados de alta en ningún aula, se mostrará el listado de mentores
     * con la condición de que estos mentores pertenezcan a su mismo area de estudios
     */
    public function index(){
        $user_type = 2;
        $users = User::where('study_area', Auth::user()->study_area)
                     ->where('user_type' , $user_type)
                     ->get();

        $image_binary     = Auth::user()->image;
        $image_data       = base64_decode($image_binary);
        $destination_path = public_path('photos\my_image.png');
        file_put_contents($destination_path, $image_data);

        return view('students.index', compact('users'));
    }
}

