<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2023-03-06 23:16:17
 * @Description: En este controlador nos encargaremos de gestionar las diferentes rutas de la parte de usuarios. Las funciones simples se encargarán de mostrar las vistas principales y
 *               las funciones acabadas en store se encargarán de la gestión de datos, tanto del alta, como consulta o modificación de los datos. Tendremos que gestionar las contraseñas,
 *               encriptandolas y gestionando hashes para controlar que no se hayan corrompido las tuplas.
 */


class UsersController extends Controller
{
    /**
     * Apartado para visualización de vistas principales
     */
    public function index(){
        return view('users.index');
    }

    public function create(){
        return view('users.create');
    }

    public function show($loginResult){
        return view('users.show');
    }

    /**
     * Apartado para gestión de datos de entrada
     */
    public function store(Request $request){
        $user = new User();
        $user = User::where('Usuario', $request->user)->where('clave', $request->password)->get();

        if (count($user) == 0){
            return view('users/accesError');
        }else{
            return "ENTRA";
        }
    }

    public function create_store(Request $request){
        $user = new User();
        $repit_password     = $request->repeat_password;

        $user->nombre        = $request->name;
        $user->apellidos     = $request->surname;
        $user->email         = $request->mail;
        $user->usuario       = $request->user;
        $user->clave         = $request->password;
        $user->mentor        = $request->mentor;
        $user->campo_estudio = $request->study_area;

        if ($repit_password == $user->clave){
            $user->save();
            return view('users.index');
        }else{
            return "ERROR";
        }
    }


}
