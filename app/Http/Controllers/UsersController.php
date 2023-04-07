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
     * ==============
     * Función index, muestra la ventana de inicio de sesión. en la función store se controla el inicio de sesión, es decir
     * se comprueba la contraseña y el usuario con la base de datos y en caso de que exista se coge el rol del usuario para
     * redireccionarlo a la vista adecuada.
     */
    public function index(){
        return view('users.index');
    }
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
    /**
     * Crear usuario
     * =============
     * Función index muestra el formulario de los datos que se necesitan para introducirlos en la base de datos. En la función
     * store se realizan distintas comprobaciones y en caso de que se cupmlan las condiciones se insertan los datos en la base de datos,
     * se redirecciona al usuario a la página de inicio de sesión.
     *
     * TO DO:
     * ======
     * Confirmación de la creación de la cuenta por correo electrónico.
     */
    public function create(){
        return view('users.create');
    }
//--------------------------------------------------------------------------------------------------
    public function create_store(Request $request){
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
     * =======================

     * Función modify carga los datos en una variable y muestra la vista correspondiente con los datos introducidos para que el usuario pueda verlos
     * y modificarlos.
     * En la función store se cargan los datos en la tupla adecuada y se hace el update de estos para modificarlo
     * de la base de datos.
     *
     * TO DO:
     * =====
     * Falta hacer las comprobaciones de los datos antes de hacer el update.
     * Pedir la contraseña cuando se pulse el botón para asegurarnos de que es el usuario quien quiere modificar la contraseña.
     */
    public function modify(){
       $data = [
            'name'        => Auth::user()->name        ,
            'surname'     => Auth::user()->surname     ,
            'email'       => Auth::user()->email       ,
            'user'        => Auth::user()->user        ,
            'tipousuario' => Auth::user()->usertype    ,
            'campoestudio' =>Auth::user()->studyarea   ,
            'description' => Auth::user()->description
       ];

        return (view('users.modify', ['data' => $data]));

    }
//--------------------------------------------------------------------------------------------------
    public function modify_store(Request $request){
        //self::validate_user_data($request);
        $user = self::complet_users_model($request);

        $actual_data = User::find(Auth::user()->id);

        $actual_data->name        = $user->name        ;
        $actual_data->surname     = $user->surname     ;
        $actual_data->email       = $user->email       ;
        $actual_data->user        = $user->user        ;
        $actual_data->user_type    = $user->user_type  ;
        $actual_data->study_area   = $user->study_area ;
        $actual_data->description = $user->description ;

        $actual_data->update();
    }
//--------------------------------------------------------------------------------------------------
    /**
     * Borrar usuario
     * ==============
     * En la función delete se cargan los datos en una variable y se pasan a la vista para que los muestre, se bloquea para que el usuario
     * no pueda modificar los datos.
     * Función delete_store se borran los datos y se redirige a la ventana de home.
     *
     * TO DO:
     * ======
     * Pedir la contraseña antes de borrar los datos, para asegurarnos de que realmente quieren borrar los datos.
     * Borrar la caché de la pagina web para que al darle atrás no vaya a ninguna página.
     */
    public function delete(){
        $data = [
            'name'      => Auth::user()->name   ,
            'surname'   => Auth::user()->surname,
            'email'     => Auth::user()->email  ,
            'user'      => Auth::user()->user   ,
            'tipousuario' => Auth::user()->usertype,
            'campoestudio' =>Auth::user()->studyarea,
            'description' => Auth::user()->description
        ];

        return view('users.delete', ['data' => $data]);
    }
//--------------------------------------------------------------------------------------------------
    public function delete_store(Request $request){
        $user = User::findOrFail(Auth::user()->id);
        $user->delete();
        return view('home');
    }
//--------------------------------------------------------------------------------------------------
    /**
     * Cerrar sesión:
     * ==============
     * Ejecutamos la función de logout y redirigimos a la página de inicio.
     *
     * TO DO:
     * ======
     * Borrar caché del navegador para que al dar hacia atrás no vuelva a la pagina anterior y que no pueda acceder a su cuenta de ninguna manera
     */
    public function close (){
        Auth::logout();
        return view('users.close');
    }
//--------------------------------------------------------------------------------------------------
    /**
     * Funciones auxiliares
     * ====================
     * Funciones con utilidades distintas.
     *
     * TO DO:
     * ======
     * Abstraerlo más y darle un uso más genérico, separar la comrobación de los datos para hacerlo sin contraseña con contraseña y sin que sean únicos los datos.
     */
    private function validate_user_data(Request $request){
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
//--------------------------------------------------------------------------------------------------
    private function complet_users_model(Request $request){
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
//--------------------------------------------------------------------------------------------------
    private function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }
//--------------------------------------------------------------------------------------------------
}
