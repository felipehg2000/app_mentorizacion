<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Exception;

use App\Models\User;
use App\Models\Student;
use App\Models\Mentor;
use App\Models\Study_room;
use App\Models\Study_room_acces;
use App\Models\Friend_request;
use App\Models\Seen_task;

use App\DataTables\UsersDataTable;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-20 20:48:17
 * @Description: En este controlador nos encargaremos de gestionar las diferentes rutas de la parte de usuarios. Las funciones simples se encargarán de mostrar las vistas principales y
 *               las funciones acabadas en store se encargarán de la gestión de datos, tanto del alta, como consulta o modificación de los datos. Tendremos que gestionar las contraseñas,
 *               encriptandolas y gestionando hashes para controlar que no se hayan corrompido las tuplas.
 * @Problems: los modelos tienen una diferenciación entre mayusculas y minusculas, al estar los atributos de la base de datos en mayusculas tengo que hacer las comprobaciones en mayusculas
 *            porque si no no estoy cogiendo los datos correctamente
 */


class UsersController extends Controller
{
//--Gestión de las funciones de los administradores-------------------------------------------------
    /**
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos la vista de crear administradores para el tipo de usuario superadmin. Admin con id = 1}
     */
    public function create_admin(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.create');
    }

    /**
     * Creamos tuplas nuevas de USUARIOS para el tipo de usuario administrador.
     *
     * @param  {Vector con los datos del formulario create del tipo de usuario admin, los de la tabla USUARIOS. (NOMBRE, APELLIDOS, EMAIL, USUARIO, CONTRASEÑA, DESCRIPCIÓN)} request
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado y la validación de datos es incorrecta devolvemos un false en respuesta ajax especificando el tipo}
     *         {Si la tupla de la base de datos se crea correctamente devolvemos un true en respuesta ajax}
     */
    public function create_admin_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        try {
            $validacion = $request->validate([
                'email'           => ['max:255', 'required', 'unique:users'],
                'user'         => ['max:30' , 'required', 'unique:users']
            ]);
        }catch(Exception $e){
            return response()->json(['success'  => false,
                                     'validate' => false]);
        }

        $new_admin = new User();

        $new_admin->name        = $request->nombre   ;
        $new_admin->surname     = $request->apellidos;
        $new_admin->email       = $request->email    ;
        $new_admin->user        = $request->user     ;
        $new_admin->password    = $this->cifrate_private_key ($request->password);
        $new_admin->user_type   = 3;
        $new_admin->study_area  = 0;
        $new_admin->description = $request->description;
        $new_admin->banned      = 0;

        $new_admin->save();

        return response()->json(['success' => true]);
    }

    /**
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos la vista de modificar datos, con los datos del usuario logueado}
     */
    public function modify_admin(){
        if (!Auth::check()){
            return view('users.close');
        }

        $admin = [
            'name'        => Auth::user()->NAME        ,
            'surname'     => Auth::user()->SURNAME     ,
            'email'       => Auth::user()->EMAIL       ,
            'user'        => Auth::user()->USER        ,
            'description' => Auth::user()->DESCRIPTION
            ];

        return view('admins.modify', ['admin' => $admin]);
    }

    /**
     * Modificamos los datos de la tabla USUARIOS de la tupla con id igual al usuario logueado.
     *
     * @param  {Vector con los datos del formulario modificar del tipo de usuario admin, los de la tabla USUARIOS. (NOMBRE, APELLIDOS, EMAIL, USUARIO, CONTRASEÑA, DESCRIPCIÓN)} request
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado y la validación de datos es incorrecta devolvemos un false en respuesta ajax especificando el tipo}
     *         {Si hay un usuario logueado y el usuario a modificar no se encuentra devolveremos un false en la respuesta ajax especificando el tipo}
     *         {Si la tupla de la base de datos se modifica correctamente devolvemos un true en respuesta ajax}
     */
    public function modify_admin_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $admin = User::find(Auth::user()->id);

        if ($admin){

            try {
                $validacion = $request->validate([
                    'email'           => ['max:255', 'required', 'unique:users'],
                    'user'         => ['max:30' , 'required', 'unique:users']
                ]);
            }catch(Exception $e){
                return response()->json(['success'  => false,
                                         'validate' => false]);
            }

            $admin->name        = $request->nombre   ;
            $admin->surname     = $request->apellidos;
            $admin->email       = $request->email    ;
            $admin->user        = $request->user     ;
            if ($request->password != ""){
                $admin->password    = $this->cifrate_private_key ($request->password);
            }
            $admin->description = $request->description;

            $admin->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success'  => false,
                                     'validate' => true]);
        }
    }

    /**
     * Mostramos el data table con la consulta cargada con todos los administradores excepto el superadmin.
     *
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos la vista de borrar administradores para el tipo de usuario superadmin, admin con id = 1}
     */
    public function delete_admins(){
        if (!Auth::check()){
            return view('users.close');
        }

        $dataTable = new UsersDataTable();
        if (request()->ajax()){
            $query = DB::table('users')
                        ->where('USER_TYPE', '=', 3)
                        ->where('id', '!=', Auth::user()->id)
                        ->select('*');

            $action_code = '<a onclick="AdminClickTable({{ $model->id }})">
                                <i class="fa fa-trash" style="font-size:16px;color:red;margin-left: -2px"></i>
                            </a>';

            return DataTables::of($query)
                              ->editColumn('BANNED', function($query){
                                    if($query->BANNED == 0) {
                                        return 'No baneado';
                                    } else if($query->BANNED == 1) {
                                        return 'Baneado';
                                    }
                                })
                              ->addColumn('action', $action_code)
                              ->rawColumns(['action'])
                              ->toJson();
        }

        return $dataTable->render('admins.delete');
    }

    /**
     * Eliminamos los datos de la tabla USUARIOS de la tupla con id igual al usuario logueado.
     *
     * @param  {Identificador del usuario administrador que queramos eliminar} request
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado y el id a eliminar es el nuestro propio, el del superadmin, devolvemos false en la respuesta ajax}
     *         {Si no se encuentra el usuario con el id parametrizado devolvemos false en la respuesta ajax}
     *         {Si la tupla de la base de datos se elimina correctamente devolvemos un true en respuesta ajax}
     */
    public function delete_admins_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->id == $request->id){
            return response()->json(['success' => false]);
        }

        $user = User::find($request->id);

        if ($user){
            $user->delete();
            return response()->json(['success' => true]);
        } else{
            return response()->json(['success' => false]);
        }
    }

//--Gestión del inicio de sesión--------------------------------------------------------------------
    /**
     * @return {Devolvemos la vista del inicio de sesión}
     */
    public function index(){
        return view('users.index');
    }

    /**
     * Comprobamos usuario y contraseña y en caso de encontrar un usuario, cogemos imagen de perfil logeamos y redireccionamos.
     *
     * @param {Vector con usuario y contraseña puestos en el formulario}
     * @return {Mensaje de error en caso de que el usuario y la contrseña no sean correctos}
     *         {Si el usuario y la contraseña son correctos devolvemos la vista correspondiente al tipo de usuario que haya iniciado sesión}
     */
    public function store(Request $request){
        $validacion = $request->validate([
            'user'     => ['max:30', 'required'],
            'password' => [          'required']
        ]);

        $clave_cifrada = $this->cifrate_private_key($request->password);
        $user          = User::where('user', $request->user)->where('password', $clave_cifrada)->first();

        if ($user != NULL) {
            if ($user->BANNED == 1) {
                return view('users.banned');
            }

            $ruta_original = public_path('photos/Perfiles/' . $user->IMAGE);
            $ruta_clon     = public_path('photos/my_image.JPG');
            File::copy($ruta_original, $ruta_clon);

            Auth::login($user);

            if ($user->USER_TYPE == 1){
                return redirect()->route('users.task_board');
            }else if($user->USER_TYPE == 2){
                return redirect()->route('users.task_board');
            } else if($user->USER_TYPE == 3) {
                return redirect()->route('admin.rep_requests');
            }
        }
        else{
            return redirect()->back()->withErrors(['message' => 'El correo electrónico o la contraseña son incorrectos.']);
        }
    }
//--Gestión de las solicitudes de amistad-----------------------------------------------------------
    /**
     * Manejar solicitudes de amistad
     * ==============================
     * Función que comprueba los datos de login del usuario logueado y redirige según el rol del usuario
     */
    public function friendship(){
        if (Auth::check()){
            $user_type = Auth::user()->USER_TYPE;
            if ($user_type == 1) {
                $tipo_usu = 2;
                $users     = User::where('STUDY_AREA', Auth::user()->STUDY_AREA)
                                ->where('USER_TYPE' , $tipo_usu              )
                                ->get();

                $titulo = '';
                if (!$users->isEmpty()){
                    $titulo = 'Solicitudes de amistad:';
                }

            } else if($user_type == 2) {
                $users = DB::table('USERS')
                         ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.STUDENT_ID', '=', 'USERS.ID')
                         ->where('FRIEND_REQUESTS.MENTOR_ID', '=', Auth::user()->id)
                         ->where('FRIEND_REQUESTS.STATUS', '=', 1)
                         ->select('USERS.*')
                         ->get();


                $titulo = '';

                if (!$users->isEmpty()){
                    $titulo = 'Solicitudes de amistad:';
                }
            }

            return view ('users.friendship', compact('users', 'titulo', 'user_type'));
        }else {
            return view('users.close');
        }

    }

    public function friendship_store(Request $request){
        if (!Auth::check()) {
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1){
            $this->frinedship_store_student($request);
        } else if (Auth::user()->USER_TYPE == 2){
            $this->frinedship_store_mentor($request);
        }

        return redirect()->back();
    }

    private function frinedship_store_student(Request $request) {
        if(!Auth::check()){
            return view('user.close');
        }
        /**Dar de alta la entrada para que los usuarios y los mentroes queden conectados. */
        $student_id = Auth::user()->id;
        $mentor_id  = DB::table('USERS')->select('ID')->where('user', $request->user_user)->get()->first()->ID;

        $resultado = Friend_request::where('MENTOR_ID' , $mentor_id)
                                   ->where('STUDENT_ID', $student_id)
                                   ->first();

        if ($resultado == NULL){
            $friendRequest = new Friend_request();
            $friendRequest->mentor_id  = $mentor_id ;
            $friendRequest->student_id = $student_id;
            $friendRequest->status     = 1          ;

            $friendRequest->save();
        } else{

            DB::table('friend_requests')
            ->where('mentor_id', $mentor_id)
            ->where('student_id', $student_id)
            ->update([
                'status' => 1,
                'seen_by_mentor' => 0,
                'updated_at' => now()
            ]);
        }
    }

    private function frinedship_store_mentor(Request $request) {
        $mentor_id  = Auth::user()->id;
        $student_id = DB::table('USERS')->select('ID')->where('user', $request->user_user)->get()->first()->ID;
        if ($request->respuesta == "ACEPTAR"){
            //Aceptar petición
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->update(['STATUS' => 2]);

              $this->CreateStudyRoomAcces($student_id, $mentor_id);
              $this->CreateSeenTasks($student_id);

        }else if ($request->respuesta == "DENEGAR"){
            //Borrar petición
            DB::table('FRIEND_REQUESTS')
              ->where('STUDENT_ID', '=', $student_id)
              ->where('MENTOR_ID', $mentor_id)
              ->delete();
        }
    }

    private function CreateStudyRoomAcces($param_student_id, $param_mentor_id) {
        if(!Auth::check()){
            return view('user.close');
        }

        $studyRoomAccess = Study_room_acces::where('STUDENT_ID', '=', $param_student_id)
                                            ->where('STUDY_ROOM_ID', '=', $param_mentor_id)
                                            ->first();

        if ($studyRoomAccess == NULL) {
            $new_node = new Study_room_acces();
            $new_node->student_id    = $param_student_id ;
            $new_node->study_room_id = $param_mentor_id;
            $new_node->logic_cancel  = '0'               ;

            $new_node->save();
        } else {

            DB::table('study_room_access')
            ->where('STUDENT_ID', $param_student_id)
            ->where('STUDY_ROOM_ID', $param_mentor_id)
            ->update([
                'logic_cancel' => 0,
                'updated_at' => now()
            ]);
        }
    }

    private function CreateSeenTasks($param_student_id){
        if (Auth::user()->USER_TYPE == 2){
            //Buscamos los estudiantes de la sala de estudios
            $tasksIds = DB::table('tasks')
                           ->where('STUDY_ROOM_ID', '=', Auth::user()->id)
                           ->select('id')
                           ->get();

            foreach($tasksIds as $id) {
                $nuevo_nodo = new Seen_task();

                $nuevo_nodo->task_id = $id->id;
                $nuevo_nodo->user_id = $param_student_id;
                $nuevo_nodo->seen_task = 0;

                $nuevo_nodo->save();
            }
        }
    }

//--Gestión de la sala de estudios------------------------------------------------------------------
    /**
     * Manejar amistades actuales
     * ==========================
     * Función que comprueba los datos de login del usuario logueado y redirige según el rol del usuario
     */
    public function actual_friends(){
        if (Auth::check()){
            $user_type = Auth::user()->USER_TYPE;
            if($user_type == 1){
                //Buscar los usuarios relacionados con nosotros
                $result_users = DB::table('users')
                ->join('study_room_access', 'users.id', '=', 'study_room_access.STUDY_ROOM_ID')
                ->where('study_room_access.STUDENT_ID', '=', Auth::user()->id)
                ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                ->select('users.*')
                ->get();

            }else if ($user_type == 2){
                //Buscar los usuarios relacionados con nosotros
                $result_users = DB::table('users')
                                  ->join('study_room_access', 'study_room_access.STUDENT_ID', '=', 'users.id')
                                  ->where('study_room_access.STUDY_ROOM_ID', '=', Auth::user()->id)
                                  ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                                  ->select('users.*')
                                  ->get();
            }
            return view('users.actual_friends', compact('result_users', 'user_type'));
        } else {
            return view('users.close');
        }
    }

    public function actual_friends_store(Request $request){
        if (!Auth::check()) {
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1){
            $this->actual_friends_store_students($request);
        } else if (Auth::user()->USER_TYPE == 2){
            $this->actual_friends_store_mentors($request);
        }

        return response()->json(['success' => true]);
    }

    private function actual_friends_store_students(Request $request){
        $student_id = Auth::user()->id;
        $mentor_id  = $request->id_user;

        DB::table('FRIEND_REQUESTS')
          ->where('STUDENT_ID', '=', $student_id)
          ->where('MENTOR_ID' , '=', $mentor_id )
          ->delete();

        DB::table('STUDY_ROOM_ACCESS')
          ->where('student_id', $student_id)
          ->update(['logic_cancel' => '1']);
    }

    private function actual_friends_store_mentors(Request $request){
        $student_id = $request->id_user;
        $mentor_id  = Auth::user()->id;
        DB::table('FRIEND_REQUESTS')
          ->where('STUDENT_ID', '=', $student_id)
          ->where('MENTOR_ID' , '=', $mentor_id)
          ->delete();

        DB::table('STUDY_ROOM_ACCESS')
          ->where('student_id', $student_id)
          ->update(['logic_cancel' => '1']);
    }

//--Gestión de la creación de usuarios--------------------------------------------------------------

    /**
     * @param {Vista create de usuarios}
     */
    public function create(){
        return view('users.create');
    }

    /**
     * Se valida la información que nos llega del formulario y cremos las tuplas necesarias
     *
     * @param {Todos los datos necesarios para crear un usuario y los datos asociados al tipo de usuario} request
     * @return {Si no ha habido ningún tipo de error devolvemos la vista home}
     */
    public function create_store(Request $request){
        $this->validate_user_data($request);

        $user = self::complet_users_model($request);
        $user->save();

        if ($request->tipousuario == 1){
            $student = self::complet_students_model($user, $request);
            $student->save();
        } else if ($request->tipousuario == 2){
            $mentor = self::complet_mentors_model($user, $request);
            $mentor->save();

            $this->CreateStudyRoom($mentor->user_id);
        }

        return view('home');
    }
//--Gestión de la modificación de usuarios----------------------------------------------------------

    /**
     * @return  {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *          {Si hay un usuario logueado devolveremos la vista para modificar los datos del usuario con los datos actuales de este}
     */
    public function modify(){
        if (Auth::check()){
            $data = [
                    'name'        => Auth::user()->NAME        ,
                    'surname'     => Auth::user()->SURNAME     ,
                    'email'       => Auth::user()->EMAIL       ,
                    'user'        => Auth::user()->USER        ,
                    'tipousuario' => Auth::user()->USER_TYPE   ,
                    'campoestudio' =>Auth::user()->STUDY_AREA  ,
                    'description' => Auth::user()->DESCRIPTION
                    ];

            if (Auth::user()->USER_TYPE == 1) { //Estudiante
                $student_data = Student::find(Auth::user()->id);

                $data['career'    ] = $student_data->CAREER    ;
                $data['first_year'] = $student_data->FIRST_YEAR;
                $data['duration'  ] = $student_data->DURATION  ;

            } else if (Auth::user()->USER_TYPE == 2) { //Mentor
                $mentor_data = Mentor::find(Auth::user()->id);

                $data['company'] = $mentor_data->COMPANY;
                $data['job'    ] = $mentor_data->JOB    ;
            }

            return (view('users.modify', ['data' => $data]));
        }else {
            return (view('users.close'));
        }

    }

    /**
     * @param {Vector con todos los datos de un usuario, también los datos asociados al tipo de usuario que es} request
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {En caso de que alguna tupla no se pueda guardar correctamente devolveremos false en la respuesta ajax}
     *         {Si todas las tuplas se modifican correctamente devolveremos true en la respuesta ajax}
     */
    public function modify_store(Request $request){
        if (Auth::check()){
            $id = Auth::user()->id;

            //Actualizamos los datos generales de usuarios
            $actual_data = User::find($id);

            $actual_data->NAME        = $request->name        ;
            $actual_data->SURNAME     = $request->surname     ;
            $actual_data->EMAIL       = $request->email       ;
            $actual_data->USER        = $request->user        ;
          //$actual_data->USER_TYPE   = $request->tipousuario ;
            $actual_data->STUDY_AREA  = $request->campoestudio;
            $actual_data->DESCRIPTION = $request->description ;

            try{
                $actual_data->save();
            } catch(Exception $e) {
                return response()->json(['success' => false]);
            }

            //Actualizamos los datos específicos de los uaurios
            if ($actual_data->USER_TYPE == 1){ //estudiante

                $actual_student_data = Student::find($id);

                $actual_student_data->CAREER     = $request->career    ;
                $actual_student_data->FIRST_YEAR = $request->first_year;
                $actual_student_data->DURATION   = $request->duration  ;

                try{
                    $actual_student_data->save();
                } catch(Exception $e){
                    return response()->json(['success' => false]);
                }


            } else if ($actual_data->USER_TYPE == 2) { //mentor
                $actual_mentor_data = Mentor::find($id);

                $actual_mentor_data->COMPANY = $request->company;
                $actual_mentor_data->JOB     = $request->job    ;

                try{
                    $actual_mentor_data->save();
                } catch(Exception $e){
                    return response()->json(['success' => false]);
                }
            }

            return response()->json(['success' => true]);

        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * @return  {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *          {Si hay un usuario logueado devolveremos la vista para modificar la contraseña}
     */
    public function modify_password(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.modify_password');
    }

    /**
     * @param {Antigua contraseña y nueva contraseña que queremos asociar a nuestro usuario}
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay un usuario logueado y la contraseña actual que nos llega en el request y la real no son la misma devolvemos false como respuesta ajax}
     *         {Si hay un usuario logueado y la contraseña actual que nos llega en el request y la real son la misma guardamos la modificación y devolvemos true como respuesta ajax}
     */
    public function modify_password_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if ($this->cifrate_private_key($request->actual_pass) !== Auth::user()->PASSWORD){
            return response()->json(['success' => false]);
        }

        $user = User::find(Auth::user()->id);

        $user->password = $this->cifrate_private_key($request->nueva_pass);
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * @return  {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *          {Si hay un usuario logueado devolveremos la vista para modificar la imágen de perfil}
     */
    public function modify_img_perf(){
        if (!Auth::check()){
            return view('users.close');
        }
        $tipo_img = Auth::user()->IMAGE;
        $tipo_usu = Auth::user()->USER_TYPE;

        if ($tipo_usu == 1 || $tipo_usu == 2) {
            return view('users.change_img_perf', compact('tipo_img', 'tipo_usu'));
        } elseif($tipo_usu == 3){
            return view('admins.change_img_perf', compact('tipo_img', 'tipo_usu'));
        }
    }

    /**
     * Hacemos una copia para la imagen de perfil temporal.
     *
     * @param {Nombre de la nueva imagen de perfil que ha seleccionado el usuario} request->img_seleccionada
     * @return  {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *          {Si hay un usuario logueado y guardamos correctamente la modificación de la tupola true como respuesta ajax}
     */
    public function modify_img_perf_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $usuario = User::find(Auth::user()->id);
        $usuario->image = $request->img_seleccionada;
        $usuario->save();

        $ruta_original = public_path('photos/Perfiles/' . $request->img_seleccionada);
        $ruta_clon     = public_path('photos/my_image.JPG');
        File::copy($ruta_original, $ruta_clon);

        return response()->json(['success' => true]);
    }

//--Gestión de la eliminación de usuarios-----------------------------------------------------------
    /**
     * @return  {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *          {Si hay un usuario logueado devolveremos la vista para eliminar los datos del usuario con los datos actuales de este}
     */
    public function delete(){
        if (Auth::check()) {
            $data = [
                'name'          => Auth::user()->NAME       ,
                'surname'       => Auth::user()->SURNAME    ,
                'email'         => Auth::user()->EMAIL      ,
                'user'          => Auth::user()->USER       ,
                'tipousuario'   => Auth::user()->USER_TYPE  ,
                'campoestudio'  => Auth::user()->STUDY_AREA  ,
                'description'   => Auth::user()->DESCRIPTION
            ];

            if (Auth::user()->USER_TYPE == 1) { //Estudiante
                $student_data = Student::find(Auth::user()->id);

                $data['career'    ] = $student_data->CAREER    ;
                $data['first_year'] = $student_data->FIRST_YEAR;
                $data['duration'  ] = $student_data->DURATION  ;

            } else if (Auth::user()->USER_TYPE == 2) { //Mentor
                $mentor_data = Mentor::find(Auth::user()->id);

                $data['company'] = $mentor_data->COMPANY;
                $data['job'    ] = $mentor_data->JOB    ;

            }

            return view('users.delete', ['data' => $data]);

        }else {
            return view('users.close');
        }
    }

    /**
     * @return  {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *          {Si el usuario está logueado y podemos borrarlo correctamente devolvemos true como respuesta ajax}
     */
    public function delete_store(Request $request){
        if (Auth::check()){
            $user = User::findOrFail(Auth::user()->id);
            $user->delete();
            return response()->json(['success' => true]);
        }else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * @param {Contraseña que el usuario ha introducido para corroborar su identidad} request->password
     * @return {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *         {Si un usuario está logueado pero la contraseña introducida no coincide con la contraseña del usuario logueado devolvemos false como respuesta ajax}
     *         {Si un usuario está logueado y la contraseña introducida coincide con la contraseña del usuario logueado devolvemos tdrue como respuesta ajax}
     */
    public function check_password_store(Request $request){
        $ret_resultado = false;
        if (Auth::check()){
            $id = Auth::user()->id;
            $usuario_autenticado = User::find($id);

            $clave_cifrada = $this->cifrate_private_key($request->password);
            if ($clave_cifrada == $usuario_autenticado->PASSWORD){
                $ret_resultado = true;
                return response()->json(['success' => $ret_resultado]);
            } else {
                return response()->json(['success' => $ret_resultado]);
            }
        } else {
            return response()->json(['success' => $ret_resultado]);
        }
    }

//--Cerrar sesión-----------------------------------------------------------------------------------
    /**
     * Borramos la copia de la foto de perfil que se hace para mostrar continuamente. Y hacemos logout
     *
     * @return {Vista de sesión cerrada}
     */
    public function close (){
        $ruta_archivo = public_path('photos/my_image.JPG');
        if (File::exists($ruta_archivo)) {
            File::delete($ruta_archivo);
        }

        Auth::logout();
        return view('users.close');
    }
//--Funciones auxiliares----------------------------------------------------------------------------
    /**
     * @param {Todos los datos asociados a la creación de usuarios (Nombre, apellidos, email, usuario, contraseña, campo de estudio y tipo de usuario)}request
     */
    private function validate_user_data(Request $request){
        $validacion = $request->validate([
            'name'          => ['max:30' , 'min:5', 'required'                ],
            'surname'       => ['max:90'                                      ],
            'email'         => ['max:255',          'required', 'unique:users'],
            'user'          => ['max:30' ,          'required', 'unique:users'],
            'password'      => [           'min:5', 'required'                ],
            'rep_password'  => [           'min:5', 'required'                ],

            'campoestudio'  => ['not_in:0'],
            'tipousuario'   => ['not_in:0']

        ]);
    }

    /**
     * @param {Todos los datos asociados a la tabla USERS}
     * @return {Modelo User relleno con los datos}
     */
    private function complet_users_model(Request $request){
        $user              = new User();
        $user->name        = $request->name;
        $user->surname     = $request->surname;
        $user->email       = $request->email;
        $user->user        = $request->user;
        $user->password    = $this->cifrate_private_key ($request->password);
        $user->user_type   = $_POST["tipousuario"];
        $user->study_area  = $_POST["campoestudio"];
        $user->description = $request->description;
        $user->banned      = 0;

        return $user;
    }

    /**
     * @param {Modelo relacionado con el usuario} user
     *        {Datos relacionados con el tipo específico del usuario, en este caso estudiante} request
     * @return {Modelo de estudiante relleno}
     */
    private function complet_students_model(User $user, Request $request){

        $student             = new Student();
        $student->user_id    = User::where('USER' , $user->user)
                                   ->where('EMAIL', $user->email)
                                   ->pluck('ID')
                                   ->first();
        $student->career     = $request->career;
        $student->first_year = $request->first_year;
        $student->duration   = $request->duration;


        return $student;
    }

    /**
     * @param {Modelo relacionado con el usuario} user
     *        {Datos relacionados con el tipo específico del usuario, en este caso mentor} request
     * @return {Modelo de mentor relleno}
     */
    private function complet_mentors_model(User $user, Request $request){
        $mentor          = new Mentor();
        $mentor->user_id = User::where('USER' , $user->user)
                               ->where('EMAIL', $user->email)
                               ->pluck('ID')
                               ->first();
        $mentor->company = $request->company;
        $mentor->job     = $request->job;

        return $mentor;
    }

    /**
     * Crea una entrada en la tabla STUDY_ROOMS
     *
     * @param {Identificador del usuario mentor} param_mentor_idç
     */
    private function CreateStudyRoom($param_mentor_id) {
        $study_room = new Study_room();

        $study_room->mentor_id = $param_mentor_id;
        $study_room->color     = 'Blue';

        $study_room->save();
    }
//--------------------------------------------------------------------------------------------------
}
