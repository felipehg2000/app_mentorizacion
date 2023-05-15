<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\MentorsController;



/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2023-05-14 20:43:05
 * @Description: En este controlador nos encargaremos de gestionar las diferentes rutas de la parte de usuarios. Las funciones simples se encargarán de mostrar las vistas principales y
 *               las funciones acabadas en store se encargarán de la gestión de datos, tanto del alta, como consulta o modificación de los datos. Tendremos que gestionar las contraseñas,
 *               encriptandolas y gestionando hashes para controlar que no se hayan corrompido las tuplas.
 * @Problems: los modelos tienen una diferenciación entre mayusculas y minusculas, al estar los atributos de la base de datos en mayusculas tengo que hacer las comprobaciones en mayusculas
 *            porque si no no estoy cogiendo los datos correctamente
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

        if ($user != NULL) {
            Auth::login($user);
            if ($user->USER_TYPE == 1){
                $controlador_estudiante = new StudentsController();
                $vista_estudiante = $controlador_estudiante->index();

                return $vista_estudiante;
            }
            else{
                $controlador_mentores = new MentorsController();
                $vista_mentor = $controlador_mentores->index();

                return $vista_mentor;
            }
        }
        else{
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
        echo 'UsersController.php create_store line 86 change implementation';
        echo $request;
        /*https://styde.net/laravel-6-doc-validacion/ */

        /*self::validate_user_data($request);


        if (self::cifrate_private_key($request->REP_PASSWORD) == $user->PASSWORD)
            {
            $user->save();
            return view('users.index');
            }
        else
            {
            return redirect()->back()->withErrors(['message' => 'Las contraseñas no coinciden']);
            }*/
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
            'name'        => Auth::user()->NAME        ,
            'surname'     => Auth::user()->SURNAME     ,
            'email'       => Auth::user()->EMAIL       ,
            'user'        => Auth::user()->USER        ,
            'tipousuario' => Auth::user()->USER_TYPE   ,
            'campoestudio' =>Auth::user()->STUDY_AREA  ,
            'description' => Auth::user()->DESCRIPTION
       ];

        return (view('users.modify', ['data' => $data]));

    }
//--------------------------------------------------------------------------------------------------
    public function modify_store(Request $request){
        //self::validate_user_data($request);
        $user = self::complet_users_model($request);

        $actual_data = User::find(Auth::user()->id);

        $actual_data->NAME        = $user->NAME        ;
        $actual_data->SURNAME     = $user->SURNAME     ;
        $actual_data->EMAIL       = $user->EMAIL       ;
        $actual_data->USER        = $user->USER        ;
        $actual_data->USER_TYPE   = $user->USER_TYPE   ;
        $actual_data->STUDY_AREA  = $user->STUDY_AREA  ;
        $actual_data->DESCRIPTION = $user->DESCRIPTION ;

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
            'name'          => Auth::user()->NAME       ,
            'surname'       => Auth::user()->SURNAME    ,
            'email'         => Auth::user()->EMAIL      ,
            'user'          => Auth::user()->USER       ,
            'tipousuario'   => Auth::user()->USER_TYPE  ,
            'campoestudio'  => Auth::user()->STUDY_AREA  ,
            'description'   => Auth::user()->DESCRIPTION
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
            'NAME'          => ['max:30' , 'min:5', 'required'                ],
            'SURNAME'       => ['max:90'                                      ],
            'EMAIL'         => ['max:255',          'required', 'unique:users'],
            'USER'          => ['max:30' ,          'required', 'unique:users'],
            'PASSWORD'      => [           'min:5', 'required'                ],
            'REP_PASSWORD'  => [           'min:5', 'required'                ]
        ]);

        return $validacion;
    }
//--------------------------------------------------------------------------------------------------
    private function complet_users_model(Request $request){
        $user              = new User();
        $user->NAME        = $request->NAME;
        $user->SURNAME     = $request->SURNAME;
        $user->EMAIL       = $request->EMAIL;
        $user->USER        = $request->USER;
        $user->PASSWORD    = self::cifrate_private_key ($request->PASSWORD);
        /*$user->USER_TYPE   = $_POST["tipousuario"];
        $user->STUDY_AREA  = $_POST["campoestudio"];
        $user->DESCRIPTION = $request->DESCRIPTION;*/

        return $user;
    }
//--------------------------------------------------------------------------------------------------
    private function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }
//--------------------------------------------------------------------------------------------------
}
