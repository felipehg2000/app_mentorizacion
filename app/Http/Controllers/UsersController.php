<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2023-03-14 20:47:35
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

        $validacion = $request->validate([
            'usuario'  => ['max:30', 'required'],
            'password' => [          'required']
        ]);

        $key           = 'clave_de_cifrado_de_32_caracteres';
        $clave_cifrada = openssl_encrypt($request->password, 'aes-256-ecb', $key);
        $user          = new User();
        $user          = User::where('usuario', $request->usuario)->where('clave', $clave_cifrada)->get();

        if (count($user) == 0){
            return redirect()->back()->withErrors(['message' => 'El correo electrónico o la contraseña son incorrectos.']);
        }else{
            return "ENTRA";
        }

    }

    public function create_store(Request $request){
        /*https://styde.net/laravel-6-doc-validacion/ */
        $validacion = $request->validate([
            'name'          => ['max:30' , 'required'                ],
            'surname'       => ['max:90'                             ],
            'email'         => ['max:255', 'required', 'unique:users'],
            'usuario'       => ['max:30' , 'required', 'unique:users'],
            'password'      => [           'required'                ],
            'rep_password'  => [           'required'                ]
        ]);

        $user = new User();
        $key  = 'clave_de_cifrado_de_32_caracteres';

        $user->nombre        = $request->name;
        $user->apellidos     = $request->surname;
        $user->email         = $request->email;
        $user->usuario       = $request->usuario;
        $user->clave         = openssl_encrypt($request->password, 'aes-256-ecb', $key);
        $user->tipo_usuario  = $_POST["tipousuario"];
        $user->campo_estudio = $_POST["campoestudio"];
        $user->descripcion   = $request->description;

        if (openssl_encrypt($request->rep_password, 'aes-256-ecb', $key) == $user->clave){
            $user->save();
            return view('users.index');
        }else{
            return redirect()->back()->withErrors(['message' => 'Las contraseñas no coinciden']);
        }
    }


}
