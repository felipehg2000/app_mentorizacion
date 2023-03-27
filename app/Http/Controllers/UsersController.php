<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



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
     * Iniciar sesión
     */
    public function index(){
        return view('users.index');
    }

    public function store(Request $request){

        $validacion = $request->validate([
            'user'     => ['max:30', 'required'],
            'password' => [          'required']
        ]);

        $clave_cifrada = self::cifrate_private_key($request->password);
        $user          = User::where('user', $request->user)->where('password', $clave_cifrada)->first();

        if ($user != NULL)
            {
            Auth::login($user);
            if ($user->user_type == 1)
                {
                return view('students.index');
                }
            else
                {
                return view('mentors.index');
                }
            }
        else
        {
        return redirect()->back()->withErrors(['message' => 'El correo electrónico o la contraseña son incorrectos.']);
        }
    }

    /**
     * Crear usuario
     */
    public function create(){
        return view('users.create');
    }

    public function create_store(Request $request)
    {
        /*https://styde.net/laravel-6-doc-validacion/ */
        $validation = self::validate_user_data($request);
        $user       = self::complet_users_model($request);

        if (self::cifrate_private_key($request->rep_password) == $user->password)
            {
            $user->save();
            return view('users.index');
            }
        else
            {
            return redirect()->back()->withErrors(['message' => 'Las contraseñas no coinciden']);
            }
    }

    /**
     * Modificar datos usuario
     */
    public function modify(){
        return view('users.modify');

    }

    public function modify_store(Request $request)
    {
        self::validate_user_data($request);
    }

    /**
     * Borrar usuario
     */
    public function delete(){
        return view('users.delete');
    }

    public function delete_store(Request $request){

    }

    /**
     * Cerrar sesión
     */
    public function close (){
        Auth::logout();
        return view('users.close');
    }

    /**
     * Funciones auxiliares
     */
    private function validate_user_data(Request $request)
    {
        $validacion = $request->validate([
            'name'          => ['max:30' , 'min:5', 'required'                ],
            'surname'       => ['max:90'                                      ],
            'email'         => ['max:255',          'required', 'unique:users'],
            'user'          => ['max:30' ,          'required', 'unique:users'],
            'password'      => [           'min:5', 'required'                ],
            'rep_password'  => [           'min:5', 'required'                ]
        ]);

        return $validacion;
    }

    private function complet_users_model(Request $request)
    {
        $user              = new User();
        $user->name        = $request->name;
        $user->surname     = $request->surname;
        $user->email       = $request->email;
        $user->user        = $request->user;
        $user->password    = self::cifrate_private_key ($request->password);
        $user->user_type   = $_POST["tipousuario"];
        $user->study_area  = $_POST["campoestudio"];
        $user->description = $request->description;

        return $user;
    }

    private function cifrate_private_key ($clave)
    {
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }
}
