<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use DateTime;

use App\Http\Controllers\StudentsController;
use App\Http\Controllers\MentorsController;

use App\Models\User;
use App\Models\Student;
use App\Models\Mentor;
use App\Models\Answer;
use App\Models\Study_room;
use App\Models\Synchronous_message;
use App\Models\Task;
use App\Models\Tutoring;
use App\Models\Friend_request;
use App\Models\Seen_task;
use App\Models\Report_request;

use App\DataTables\TaskDataTable;
use App\DataTables\TutoringDataTable;
use App\DataTables\UsersDataTable;
use App\DataTables\Report_requestDataTable;

use App\Events\NewMessageEvent;
use App\Events\TutUpdateEvent;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-22 00:33:53
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
     * Devuelve la vista de reportes de los usuarios para el tipo de usuario administrador
     * con la consulta del data table cargada.
     */
    public function rep_requests(){
        if (!Auth::check()){
            return view('users.close');
        }

        $dataTable = new Report_requestDataTable();
        if (request()->ajax()){
            $query = DB::table('users')
                       ->join('report_requests', 'users.id', '=', 'report_requests.reported')
                       ->select('users.id', 'users.NAME', 'users.SURNAME', 'users.USER', 'report_requests.REASON');

            return DataTables::of($query)->toJson();
        }

        return $dataTable->render('admins.report_requests');
    }

    /**
     * Devuelve la vista de la opción de bloquear mentores para el tipo de usuario administrador
     * con la consulta del data table cargada.
     */
    public function block_mentores(){
        if (!Auth::check()){
            return view('users.close');
        }

        $dataTable = new UsersDataTable();
        if (request()->ajax()){
            $query = DB::table('users')
                        ->where('USER_TYPE', '=', 2)
                        ->select('*');

            $action_code = '<a onclick="AdminClickTable({{ $model->id }})">
                                <i class="fa fa-ban" style="font-size:16px;color:red;margin-left: -2px"></i>
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

        return $dataTable->render('admins.block_users');
    }

    /**
     * Devuelve la vista de la opción de bloquear estudiantes para el tipo de usuario administrador
     * con la consulta del data table cargada.
     */
    public function block_students(){
        if (!Auth::check()){
            return view('users.close');
        }

        $dataTable = new UsersDataTable();
        if (request()->ajax()){
            $query = DB::table('users')
                        ->where('USER_TYPE', '=', 1)
                        ->select('*');

            $action_code = '<a onclick="AdminClickTable({{ $model->id }})">
                                <i class="fa fa-ban" style="font-size:16px;color:red;margin-left: -2px"></i>
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

        return $dataTable->render('admins.block_users');
    }

    /**
     * Devuelve la vista de la opción de bloquear administradores para el tipo de usuario administrador
     * con la consulta del data table cargada. Solo es accesible para el administrador con id = 1 que
     * será el superadmin.
     */
    public function block_admins(){
        if (!Auth::check()){
            return view('users.close');
        }

        $dataTable = new UsersDataTable();
        if (request()->ajax()){
            $query = DB::table('users')
                        ->where('USER_TYPE', '=', 3)
                        ->where('id' , '!=', Auth::user()->id)
                        ->select('*');

            $action_code = '<a onclick="AdminClickTable({{ $model->id }})">
                                <i class="fa fa-ban" style="font-size:16px;color:red;margin-left: -2px"></i>
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

        return $dataTable->render('admins.block_users');
    }

    /**
     * Actualizamos la base de datos para poner el dato BANNED del usuario seleccionado a 0 o 1 dependiendo del
     * valor que tenga en el momento actual
     *
     * @param {Tiene solo un argumento, el id del usuario que queramos banear} request
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si el update de la base de datos se hace correctamente se hace una respuesta true a la llamada ajax}
     */
    public function bann_people_store(Request $request){

        if(!Auth::check()){
            return view('users.close');
        }

        $user_id = $request->id;

        $usuario = User::where('id', $user_id)->first();

        if ($usuario->BANNED == 0){
            $usuario->BANNED = 1;
        } else if($usuario->BANNED == 1){
            $usuario->BANNED = 0;
        }

        $usuario->save();
        return response()->json(['success' => true]);
    }

    /**
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos la vista de tutorial para el tipo de usuario administrador}
     */
    public function admin_tut(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.tutorial');
    }

    /**
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos la vista de novedades para el tipo de usuario administrador}
     */
    public function admin_news(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.news');
    }

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
        $new_admin->password    = self::cifrate_private_key ($request->password);
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
                $admin->password    = self::cifrate_private_key ($request->password);
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

        $clave_cifrada = self::cifrate_private_key($request->password);
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

    /**
     * Hacemos las consultas de base de datos necesarias para pasar todos los datos a la función js que controla que mostrar en cada opción.
     * Puntos de notificación cubiertas con sus mensajes específicos...
     *
     * @param {Vacío}
     * @return {Si el usuario no está logueado devolvemos un false en la respuesta ajax}
     *         {Si el usuario está logueado y es un administrador devolvemos true con id del administrador y puntos de notificación a activar en la respuesta ajax}
     *         {Si el usuario está logueado y no es un administrador devolveremos true con los datos necesarios para mostrar la pantalla correctamente}
     */
    public function info_inicial_store(Request $request){
        if (!Auth::check()){
            return response()->json(['success' => false]);
        }
        $num_estudiantes = 0;
        $tiene_solicitudes  = false;
        if (Auth::user()->USER_TYPE == 1){
            $num_salas_estudio = DB::table('study_room_access')
                                    ->where('STUDENT_ID'  , '=', Auth::user()->id)
                                    ->where('LOGIC_CANCEL', '=', 0)
                                    ->count();

            $friendRequests = DB::table('friend_requests')
                                 ->where('STUDENT_ID', '=', Auth::user()->id)
                                 ->where(function($query) {
                                     $query->where('STATUS', '=', 1)
                                             ->orWhere('STATUS', '=', 2);
                                 })
                                 ->count();

            if ($friendRequests != 0){
                $tiene_solicitudes = true;
            }

            if ($num_salas_estudio == 0){
                $tiene_sala_estudio = false;
            } else {
                $tiene_sala_estudio = true;
            }
        } else if (Auth::user()->USER_TYPE == 2) {
            $num_estudiantes = DB::table('study_room_access')
                                  ->where('STUDY_ROOM_ID'  , '=', Auth::user()->id)
                                  ->where('LOGIC_CANCEL', '=', 0)
                                  ->count();

            if ($num_estudiantes == 0){
                $tiene_sala_estudio = false;
            } else {
                $tiene_sala_estudio = true;
            }
        } else if (Auth::user()->USER_TYPE == 3){
            return response()->json(['success'            => true,
                                     'admin_id'           => Auth::user()->id,
                                     'new_report_request' => $this->NotSeenReportRequest()]);
        }

        return response()->json(['success'             => true                               ,
                                 'user_type'           => Auth::user()->USER_TYPE            ,
                                 'user_id'             => Auth::user()->id                   ,
                                 'tiene_sala_estudio'  => $tiene_sala_estudio                ,
                                 'numero_alumnos'      => $num_estudiantes                   ,
                                 'new_messages'        => $this->NotSeenSynchronousMessages(),
                                 'new_friend_requests' => $this->NotSeenFriendRequests()     ,
                                 'new_tasks'           => $this->NotSeenTasks()              ,
                                 'new_answer'          => $this->NotSeenAnswers()            ,
                                 'new_tutoring'        => $this->NotSeenTutoring()           ,
                                 'solicitud_mandada'   => $tiene_solicitudes]);
    }

//--Gestión del tablón de tareas--------------------------------------------------------------------
    /**
     * Selecciona las tareas que hay en una sala de estudio y devuelve una vista con esas tareas.
     */
    public function task_board(){
        if (Auth::check()) {
            $tipo_usu        = Auth::user()->USER_TYPE;
            $id_sala_estudio = 0;

            if ($tipo_usu == 1){ //Estudiante
                $respuesta = DB::table('study_room_access')
                                ->where('STUDENT_ID'  , '=', Auth::user()->id)
                                ->where('LOGIC_CANCEL', '=', 0)
                                ->select('STUDY_ROOM_ID')
                                ->first();
                if ($respuesta == NULL){
                    $tasks = NULL;
                    return view('users.task_board',  compact('tipo_usu', 'tasks'));
                }
                $id_sala_estudio = $respuesta->STUDY_ROOM_ID;

                $tasks = DB::table('TASKS')
                        ->where('study_room_id', $id_sala_estudio)
                        ->where('logic_cancel', 0)
                        ->orderBy('last_day', 'desc')
                        ->get();
            } else if ($tipo_usu == 2) { //Mentor
                $id_sala_estudio = Auth::user()->id;

                $tasks = DB::table('TASKS')
                        ->where('study_room_id', $id_sala_estudio)
                        ->orderBy('logic_cancel', 'asc')
                        ->orderBy('last_day', 'desc')
                        ->get();
            }

            return view('users.task_board',  compact('tipo_usu', 'tasks'));
        } else {
            return view('users.close');
        }
    }

    public function task_board_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $file = $request->fichero;
        $task_id = $request->id_task;

        $name = Auth::user()->id . '_' . $task_id . '.' . $file->extension();
        $file->storeAs('', $name, 'public');

        $task = Answer::where('TASK_ID', '=', $task_id)
                      ->where('STUDY_ROOM_ACCES_ID', '=', Auth::user()->id)
                      ->first();

        if ($task == null) {
            $this->CreateAnswer($task_id, Auth::user()->id, $name);
        }

        return response()->json(['success' => true]);
    }

    public function download_task(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $answer = DB::table('answers')
                     ->select('NAME')
                     ->where('TASK_ID', '=',             $request->id_task)
                     ->where('STUDY_ROOM_ACCES_ID', '=', $request->id_user)
                     ->first();

        if($answer == NULL){
            return response()->json(['success' => false,
                                     'message' => 'Este alumno no ha realizado ninguna entrega']);
        }
        $name = $answer->NAME;

        if (Storage::disk('public')->exists($name)){
            $path = storage_path('app\\public\\' . $name);
            $header = ['Content-Type' => 'application/pdf'];

            return response()->download($path, $name, [], 'inline');
        }else {
            dd('Entra');
            return response()->json(['success' => false,
                                     'message' => 'La entrega del alumno no se ha podido encontrar']);
        }
    }

    /**
     * Carga los datos en el modelo y crea la entrada en la base de datos. Los datos vienen comprobados
     */
    public function add_task_store(Request $request){
        if(Auth::check()) {
            $study_room_id  = Auth::user()->id           ;
            $titulo         = $request->datos['titulo_tarea'     ];
            $descripcion    = $request->datos['descripcion_tarea'];
            $fecha_hasta    = $request->datos['fecha_tarea'      ];
            $fecha_hasta    = new DateTime($fecha_hasta);
            $fecha_creacion = date('y-m-d');
            $this->CreateTask($study_room_id, $titulo, $descripcion, $fecha_hasta, $fecha_creacion);

            return response()->json(['success' => true]);
        } else {
            return view('users.close');
        }
    }

    /**
     * Actualiza los campos de la base de datos por si se ha modificado algo. La tarea que esté en $request->id_task
     */
    public function update_task_store(Request $request){
        //modify de la base de datos del id que me pasan en la request
        if (Auth::check()) {
            $task_id = $request->datos['id_tarea'];

            $tarea = Task::where('id', $task_id)->first();

            $tarea->task_title    = $request->datos['titulo_tarea'     ];
            $tarea->description   = $request->datos['descripcion_tarea'];
            $tarea->last_day      = $request->datos['fecha_tarea'      ];
            $tarea->last_day      = new DateTime($tarea->last_day);

            $tarea->save();
            return response()->json(['success' => true]);
        } else{
            return view('users.close');
        }
    }

    /**
     * Modifica el campo logic_cancel de la tabla tasks con id = $request->id_task
     */
    public function delete_task_store(Request $request){
        if (Auth::check()) {
            $task_id = $request->datos;
            $tarea = Task::where('id', $task_id)->first();
            $tarea->logic_cancel = $request->logic_cancel;
            try{
                $tarea->save();
            }catch(Exception $e) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true]);
        } else {
            return view('users.close');
        }
    }
//--Gestión de los resumenes de tareas--------------------------------------------------------------
    /**
     * Devolvemos la vista de la opción tareas finalizadas con la consulta del data table cargada de la siguiente manera:
     *      Mentor    : le mostrará las tareas cuyas fechas de último día ha pasado ya.
     *      Estudiante: le mostrará sus tareas que aún no tienen una respuesta asociada
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *
     *         {Si el usuario está logueado devolvemos la vista done_tasks con la consulta correspondiente}
     */
    public function done_tasks(){

        if (Auth::check()) {
            $dataTable = new TaskDataTable();
            if (request()->ajax()){

                $action_code = '';
                if (Auth::user()->USER_TYPE == 1) {
                    $action_code = '<a onclick="StudentClickColumnToDoTask({{ $model->id }}, false)">
                                        <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                    </a>';
                    $studyRoomId = DB::table('STUDY_ROOM_ACCESS')
                                      ->where('STUDENT_ID', Auth::user()->id)
                                      ->where('LOGIC_CANCEL', 0)
                                      ->value('STUDY_ROOM_ID');

                    $query = DB::table('TASKS')
                                ->join('ANSWERS', 'TASKS.id', '=', 'ANSWERS.TASK_ID')
                                ->where('TASKS.STUDY_ROOM_ID', $studyRoomId)
                                ->where('TASKS.LOGIC_CANCEL', 0)
                                ->select('TASKS.*');
                } elseif (Auth::user()->USER_TYPE == 2) {
                    $action_code = '<a onclick="MentorClickColumnDataTableTask({{ $model->id }})">
                                        <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                    </a>';

                    $query = DB::table('TASKS')
                                ->where('STUDY_ROOM_ID', Auth::user()->id)
                                ->whereDate('LAST_DAY', '<=', now());
                }


                return DataTables::of($query)
                                 ->editColumn('LAST_DAY', function($query){
                                     return Carbon::parse($query->LAST_DAY)->format('d-m-Y');
                                 })
                                 ->editColumn('created_at', function($query){
                                    return Carbon::parse($query->created_at)->format('d-m-Y');
                                 })
                                 ->addColumn('action', $action_code)
                                 ->rawColumns(['action'])
                                 ->toJson();
            }
            return  $dataTable->render('users.done_tasks');
        } else{
            return view('users.close');
        }
    }

    /**
     * Devolvemos la vista de la opción tareas por hacer con la consult a del data table cargada de la siguiente manera:
     *      Mentor    : le mostrará las tareas cuyas fechas de último día aún no ha pasado.
     *      Estudiante: le mostrará las tareas que tienen asociada una entrega
     *
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si el usuario está logueado devolvemos la vista done_tasks con la consulta correspondiente}
     */
    public function to_do_tasks(){
        if (Auth::check()){
            $dataTable = new TaskDataTable();
            if (request()->ajax()){
                $action_code = '';
                if (Auth::user()->USER_TYPE == 1) {
                    $action_code = '<a onclick="StudentClickColumnToDoTask({{ $model->id }}, true)">
                                        <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                    </a>';
                    $studyRoomId = DB::table('STUDY_ROOM_ACCESS')
                                      ->where('STUDENT_ID', Auth::user()->id)
                                      ->where('LOGIC_CANCEL', 0)
                                      ->value('STUDY_ROOM_ID');

                    $query = DB::table('TASKS')
                                ->leftJoin('ANSWERS', 'TASKS.id', '=', 'ANSWERS.TASK_ID')
                                ->whereNull('ANSWERS.TASK_ID')
                                ->where('TASKS.STUDY_ROOM_ID', $studyRoomId)
                                ->where('TASKS.LOGIC_CANCEL', 0)
                            ->select('TASKS.*');
                }elseif (Auth::user()->USER_TYPE == 2){
                    $action_code = '<a onclick="MentorClickColumnDataTableTask({{ $model->id }})">
                                        <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                    </a>';

                    $query = DB::table('TASKS')
                                ->where('STUDY_ROOM_ID', Auth::user()->id)
                                ->whereDate('LAST_DAY', '>', now());
                }

                return DataTables::of($query)
                                 ->editColumn('LAST_DAY', function($query){
                                     return Carbon::parse($query->LAST_DAY)->format('d-m-Y');
                                 })
                                 ->editColumn('created_at', function($query){
                                    return Carbon::parse($query->created_at)->format('d-m-Y');
                                 })
                                 ->addColumn('action', $action_code)
                                 ->rawColumns(['action'])
                                 ->toJson();
            }
            return  $dataTable->render('users.done_tasks');
        } else{
            return view('users.close');
        }
    }

    /**
     * @param {Solo tiene el dato id que es el identificador de la tarea que debemos buscara} request
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si no encontramos la tarea devolveremos false como respuesta ajax}
     *         {Si encontramos la tarea devolveremos true y los datos de la tarea como respuesta ajax}
     */
    public function found_task_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $task = Task::find($request->id);

        if ($task == NULL){
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true,
                                 'tarea'   => $task]);
    }

    /**
     * @param {Identificador de la tarea asociada a las respuestas que queremos encontrar} request->id
     * @return {Si no hay un usuario logueado devolvemos false como respuesta ajax}
     *         {Si hay usuario logueado devolveremos true, los integrantes activos de la sala de estudio y la lista de usuarios que tienen tareas hechas como respuesta ajax}
     */
    public function found_answers_store(Request $request){

        if (!Auth::check()) {
            return response()->json(['success' => false]);
        }

        /**
         * Hacemos un select de todas las personas dentro de una sala de estudio
         */

        $usuariosConAcceso = DB::table  ('users')
                                ->join  ('study_room_access', 'users.id'             , '=', 'study_room_access.STUDENT_ID'   )
                                ->join  ('study_rooms'      , 'study_rooms.MENTOR_ID', '=', 'study_room_access.STUDY_ROOM_ID')
                                ->where ('study_room_access.LOGIC_CANCEL', '=', 0)
                                ->select('users.id', 'users.NAME', 'users.SURNAME')
                                ->get();

        /**
         * Hacemos una consulta para buscar todos los usuarios de la sala de estudios, que han realizado una entrega sobre esa tarea
         */

        $mentorId = Auth::user()->id;
        $tareaId  = $request->id;

        $usuariosConRespuesta = DB::table('study_rooms')
                                    ->join('study_room_access', 'study_rooms.MENTOR_ID', '=', 'study_room_access.STUDY_ROOM_ID')
                                    ->join('tasks', function ($join) use ($tareaId) {
                                        $join->on('tasks.STUDY_ROOM_ID', '=', 'study_rooms.MENTOR_ID')
                                            ->where('tasks.id', '=', $tareaId);
                                    })
                                    ->join('answers', function ($join) use ($tareaId) {
                                        $join->on('answers.STUDY_ROOM_ACCES_ID', '=', 'study_room_access.STUDENT_ID')
                                            ->where('answers.TASK_ID', '=', $tareaId);
                                    })
                                    ->join('users', 'users.id', '=', 'study_room_access.STUDENT_ID')
                                    ->where('study_rooms.MENTOR_ID', '=', $mentorId)
                                    ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                                    ->select('users.id', 'users.NAME', 'users.SURNAME')
                                    ->get();

        return response()->json(['success'               => true,
                                 'usuarios_sala_estudio' => $usuariosConAcceso,
                                 'usuarios_con_entrega'  => $usuariosConRespuesta]);
    }

//--Gestión del chat síncrono-----------------------------------------------------------------------
    /**
     * @return {Si no hay un usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolvemos la vista del chat sincrono con una lista de los uaurios con los que podemos chatear y el tipo de usuario que somos}
     */
    public function sync_chat(){
        if (Auth::check()){
            if (Auth::user()->USER_TYPE == 1) {
                $mis_amigos = DB::table('users')
                                ->join('study_room_access', 'users.id', '=', 'study_room_access.STUDY_ROOM_ID')
                                ->where('study_room_access.STUDENT_ID', '=', Auth::user()->id)
                                ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                                ->get();
            } else{
                $mis_amigos = DB::table('users')
                                ->join('study_room_access', 'study_room_access.STUDENT_ID', '=', 'users.id')
                                ->where('study_room_access.STUDY_ROOM_ID', '=', Auth::user()->id)
                                ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                                ->get();
            }

            $tipo_usu = Auth::user()->USER_TYPE;
            return view('users.sync_chat', compact('mis_amigos', 'tipo_usu'));
        } else {
            return view('users.close');
        }
    }

    /**
     * @param {Identificador del contacto del que debemos buscar los mensajes para mostrar en el chat} request->contact_id
     * @return {Si no hay usuario logueado devolveremos false como respuesta ajax}
     *         {Si el usuario está logueado devolveremos true, los mensajes del chat y los datos del usuario seleccionado, también cancelaremos el punto de notificación}
     */
    public function sync_chat_store(Request $request){
        $id_mentor     = 0;
        $id_estudiante = 0;
        if (Auth::check()){
            if (Auth::user()->USER_TYPE == 1){ //Estudiante
                $id_mentor     = $request->contact_id;
                $id_estudiante = Auth::user()->id;
            } else if (Auth::user()->USER_TYPE == 2){ //Mentor
                $id_mentor     = Auth::user()->id;
                $id_estudiante = $request    ->contact_id;
            }

            $resultado = DB::table('synchronous_messages')
                           ->join('study_room_access', function ($join) {
                               $join->on('study_room_access.STUDENT_ID', '=', 'synchronous_messages.STUDY_ROOM_ACCES_ID')
                                    ->on('study_room_access.STUDY_ROOM_ID', '=', 'synchronous_messages.STUDY_ROOM_ID');
                           })
                           ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                           ->where('synchronous_messages.STUDY_ROOM_ID', '=', $id_mentor)
                           ->where('synchronous_messages.STUDY_ROOM_ACCES_ID', '=', $id_estudiante)
                           ->select('synchronous_messages.id', 'synchronous_messages.SENDER', 'synchronous_messages.MESSAGE')
                           ->orderBy('synchronous_messages.created_at', 'ASC')
                           ->limit(1000)
                           ->get();

            $selected_user = DB::table ('users')
                               ->where ('users.id', '=', $request->contact_id)
                               ->select('NAME', 'SURNAME')
                               ->first();

            $this->SynchronousMessagesSaw($request->contact_id);

            return response()->json(['success'       => true      ,
                                     'data'          => $resultado,
                                     'selec_user'    => $selected_user,
                                     'new_sync_chat' => $this->NotSeenSynchronousMessages()]);

        }else{
            return response()->json(['success' => false]);
        }
    }

    /**
     * Antes de mandar la información por el pusher la ciframos con nuestra función auxiliar.
     *
     * @param {id_chat como identificador del chat al que hemos enviado el mensaje. Message: el mensaje que le vamos a mandar} request
     * @return {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *         {Si el usuario está logueado devolvemos ture con nuestro id como respuesta ajax}
     */
    public function send_message_store(Request $request){
        if (Auth::check()){
            $mi_id = Auth::user()->id;
            $id_estudiante = 0;
            $id_mentor     = 0;
            $id_canal      = 0;

            //Necesito el id del estudiante y del mentor en ambos casos
            if (Auth::user()->USER_TYPE == 1){ //Estudiante
                $id_estudiante = $mi_id                  ;
                $id_mentor     = $request->datos['id_chat'];
                $id_canal      = $id_mentor;

                $this->CreateSynchronousMessage($id_mentor, $id_estudiante, $request->datos['message']);
            }else if(Auth::user()->USER_TYPE == 2){ //Mentor
                $id_mentor     = $mi_id;
                $id_estudiante = $request->datos['id_chat'];
                $id_canal      = $id_estudiante;

                $this->CreateSynchronousMessage($id_mentor, $id_estudiante, $request->datos['message']);
            }

            $id_mensaje = DB::table('synchronous_messages')
                            ->max('id');
            $message = [
                'mensaje'    => $this->cifrate_private_key($request->datos['message']), //ciframos mensaje
                'mi_id'      => Auth::user()->id          ,
                'message_id' => $id_mensaje
            ];
            //Disparador del pusher para que reciva el evento el usuario al que le mandamos el mensaje
            Event::dispatch(new NewMessageEvent($message, $id_canal));

            return response()->json(['success' => true,
                                     'mi_id'   => $mi_id]);

        }else{
            return response()->json(['success' => false]);
        }
    }
//--Gestión de de solicitud de tutorías-------------------------------------------------------------
    /**
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay un usuario logueado dvolvemos la vista de solicitudes de amistad con la query correspondiente del data table cargada}
     */
    public function tut_request(){
        if(!Auth::check()){
            return view('users.close');
        }

        $dataTable = new TutoringDataTable();
        if (request()->ajax()){
            //hacer la consulta

            $query = DB::table('users')
                        ->join('tutoring', 'users.id', '=', 'tutoring.STUDY_ROOM_ACCES_ID')
                        ->join('study_room_access', function ($join) {
                            $join->on('study_room_access.STUDENT_ID', '=', 'tutoring.STUDY_ROOM_ACCES_ID')
                                ->where('study_room_access.LOGIC_CANCEL', '=', 0);
                        })
                        ->select('users.NAME', 'users.SURNAME', 'tutoring.*');


                        $action_code = ' <a onclick="ClickDataTable({{ $model->id }})">
                                            <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                         </a>';
                        return DataTables::of($query)
                                          ->editColumn('STATUS', function($query){
                                                if ($query->STATUS == 0) {
                                                    return 'En tratmite';
                                                } else if ($query->STATUS == 1) {
                                                    return 'Aceptada';
                                                } else if ($query->STATUS == 2) {
                                                    return 'Denegada';
                                                } else if ($query->STATUS == 3) {
                                                    return 'Finalizada';
                                                }
                                          })
                                          ->editColumn('created_at', function($query){
                                                return Carbon::parse($query->created_at)->format('d-m-Y');
                                          })
                                          ->addColumn('action', $action_code)
                                          ->rawColumns(['action'])
                                          ->toJson();


                                          return DataTables::of($query)
                                          ->editColumn('LAST_DAY', function($query){
                                              return Carbon::parse($query->LAST_DAY)->format('d-m-Y');
                                          })
                                          ->editColumn('created_at', function($query){
                                             return Carbon::parse($query->created_at)->format('d-m-Y');
                                          })
                                          ->addColumn('action', $action_code)
                                          ->rawColumns(['action'])
                                          ->toJson();
        }

        $tipo_usu = Auth::user()->USER_TYPE;

        return $dataTable->render('users.tut_request', compact('tipo_usu'));
    }

    /**
     * @param {Fecha de la tutoría que queremos crear} request->fecha
     *        {Hora de la tutoría que queremos crear} request->hora
     *        {Estado de la tutoría que queremos crear} request->status
     * @return {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *         {Si hay un usuario logueado y se crea el registro correctamente devolvemos ture como respuesta ajax}
     */
    public function add_tuto_store(Request $request){
        if (!Auth::check()){
            return response()->json(['success' => false]);
        }

        //Insertar registro en la base de datos
        $date                 = $request->fecha;
        $hour                 = $request->hora;
        $status               = $request->status;
        $study_room_access_id = Auth::user()->id;

        $consulta = DB::table('study_room_access')
                       ->where('STUDENT_ID'  , '=', $study_room_access_id)
                       ->where('LOGIC_CANCEL', '=', 0)
                       ->select('STUDY_ROOM_ID')
                       ->first();


        $dateTime = $date . ' ' . $hour;

        $datetime = new DateTime($dateTime);

        $this->CreateTutoring($consulta->STUDY_ROOM_ID, $study_room_access_id, $datetime, $status);

        return response()->json(['success' => true]);
    }

    /**
     * @param {Identificador de la tutoría de la que queremos obtener los datos} request->id
     * @return {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *         {Si hay un usuario logueado y no se encuentra el registro mandamos false como respuesta ajax}
     *         {Si hay un usuario logueado y se encuentra los datos de la tutoría se devuelve true, el tipo de usuario y los datos de la tutoría como respuesta ajax}
     */
    public function get_tuto_data_store(Request $request){
        if (!Auth::check()){
            return response()->json(['success' => false]);
        }

        $datos_tutoria = Tutoring::find($request->id);

        if ($datos_tutoria == NULL){
            return response()->json(['success' => false]);
        }
        return response()->json(['success'   => true,
                                 'user_type' => Auth::user()->USER_TYPE,
                                 'tut_data'  => $datos_tutoria]);
    }

    /**
     * @param {Fecha nueva de la tutoría que queremos modificar} request->fecha
     *        {Hora nueva de la tutoría que queremos modificar} request->hora
     *        {Estado nuevo de la tutoría que queremos modificar} request->status
     * @return {Si no hay usuario logueado devolvemos false como respuesta ajax}
     *         {Si hay usuario logueado y se modifica correctamente la tutoría devolvemos true como respuesta ajax}
     */
    public function update_tuto_store(Request $request){
        if (!Auth::check()){
            return response()->json(['success' => false]);
        }
        $tutoria = Tutoring::where('id', $request->id)->first();

        $tutoria->date   = $request->fecha . ' ' . $request->hora;
        $tutoria->status = $request->status;

        $tutoria->save();
        return response()->json(['success' => true]);
    }

//--Gestión de acceso a las tutorías----------------------------------------------------------------
    /**
     * Buscamos los parametros que definen si los usuarios pueden o no acceder a la tutoría
     *
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolvemos la vista de acceso a la tutoría con los parametros necesarios para hacer la tutoría}
     */
    public function tut_access(){
        if (!Auth::check()){
            return view('users.close');
        }

        $tipo_usu  = Auth::user()->USER_TYPE;
        $id_user   = Auth::user()->id;
        $titulo    = '';
        $hora_tuto = '';
        $id_tuto   = '';

        $fechaHoy = Carbon::now()->toDateString();
        $fechaManana = Carbon::tomorrow()->toDateString();

        if ($tipo_usu == 1) {
            $fecha_hora_tuto = DB::table('tutoring')
                                  ->select('DATE', 'id')
                                  ->where('STUDY_ROOM_ACCES_ID', '=', Auth::user()->id)
                                  ->where('STATUS', '=', '1')
                                  ->where('DATE', '>=', $fechaHoy)
                                  ->where('DATE', '<', $fechaManana)
                                  ->first();
        } else if ($tipo_usu == 2) {
            $fecha_hora_tuto = DB::table('tutoring')
                                  ->select('DATE', 'id')
                                  ->where('STUDY_ROOM_ID', '=', Auth::user()->id)
                                  ->where('STATUS', '=', '1')
                                  ->where('DATE', '>=', $fechaHoy)
                                  ->where('DATE', '<' , $fechaManana)
                                  ->first();
        }

        if ($fecha_hora_tuto != NULL){
            $id_tuto  = $fecha_hora_tuto->id;
            $horaTuto = Carbon::parse($fecha_hora_tuto->DATE)->format('H:i:s');
            $horaHoy  = Carbon::now();

            if ($horaHoy->gte($horaTuto)){
                $titulo = 'Acceso a la tutoría:';
            }
        }
        return view('users.tut_access', compact('tipo_usu', 'titulo', 'id_tuto', 'id_user'));
    }

    /**
     * Mandamos la información en tiempo real a través del servidor pusher. Antes de pasar el texto se cifra la información.
     *
     * @param {Identificador del canal pusher por el que tenemos que mandar el texto} request->id_canal
     *        {Texto que tenemos que mandar por el pusher} request->texto
     * @return {Si no hay usuario logueado devolveremos la vista de sesión cerrada}
     */
    public function send_text_store(Request $request){
        if(!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1){ //Estudiante
            //Coger el id del mentor

            $id_usuario_contrario = DB::table('study_room_access')
                                       ->where('STUDENT_ID', '=', Auth::user()->id)
                                       ->select('STUDY_ROOM_ID')
                                       ->first();

            $id_usuario_contrario = $id_usuario_contrario->STUDY_ROOM_ID;
        } else if (Auth::user()->USER_TYPE == 2) { //Mentor
            //Coger el id del estudiante

            $id_usuario_contrario = DB::table('tutoring')
                                       ->where('id', '=', $request->id_channel)
                                       ->select('STUDY_ROOM_ACCES_ID')
                                       ->first();

            $id_usuario_contrario = $id_usuario_contrario->STUDY_ROOM_ACCES_ID;
        }

        $id_canal = $request->id_channel . $id_usuario_contrario;

        //Disparamos el pusher para que salte el evento al usuario conectado al canal
        Event::dispatch(new TutUpdateEvent($this->cifrate_private_key($request->texto), $id_canal));
    }

    /**
     * Borramos las imagenes que se han intercambiado los usuarios y la carpeta asociada a la
     * tutoría y cambiamos el estado de la tutoría a finalizado
     *
     * @param {Identificador de la tutoría que queremos finalizar} request->id_tuto
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Devolvemos true en caso de que la tupla se actualice correctamentew}
     */
    public function fin_tuto_store(Request $request){
        if(!Auth::check()){
            return view('users.close');
        }

        //Borramos todos los elementos de la tutoría y la carpeta que se ha creado para almacenarlos temporalmente
        $id_mentor = '';

        if (Auth::user()->USER_TYPE == 1){ //Estudiante
            $id_mentor = DB::table('study_room_access')
                           ->where('STUDENT_ID', '=', Auth::user()->id)
                           ->where('LOGIC_CANCEL', '=', 0)
                           ->select('STUDY_ROOM_ID')
                           ->first();

            $id_mentor = $id_mentor->STUDY_ROOM_ID;

        } else if (Auth::user()->USER_TYPE == 2){ //Mentor
            $id_mentor = Auth::user()->id;
        }

        $path = Storage::path('\public\images_tuto_' . $id_mentor);
        $archivos = glob($path . '/*');
        foreach ($archivos as $archivo) {
            if (is_file($archivo)) {
                unlink($archivo);
            }
        }

        if (is_dir($path)) {
            rmdir($path);
        }

        //Actualizamos el estado de la tutoría
        $tutoria = Tutoring::where('id', $request->id_tuto)->first();
        $tutoria->status = 3;
        $tutoria->save();

        return response()->json(['success' => true]);
    }

    /**
     * Subimos al storage el fichero que nos manda el componente CkEditor
     *
     * @param {Fichero que queremos gestionar} request->file()
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {URL del archivo subido al storage para que lo gestione el CkEditor}
     */
    public function upload_img_tuto_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $id_mentor = '';

        if (Auth::user()->USER_TYPE == 1){ //Estudiante
            $id_mentor = DB::table('study_room_access')
                           ->where('STUDENT_ID', '=', Auth::user()->id)
                           ->where('LOGIC_CANCEL', '=', 0)
                           ->select('STUDY_ROOM_ID')
                           ->first();

            $id_mentor = $id_mentor->STUDY_ROOM_ID;

        } else if (Auth::user()->USER_TYPE == 2){ //Mentor
            $id_mentor = Auth::user()->id;
        }

        $file = $request->file('upload');
        $path = $file->store('public/images_tuto_' . $id_mentor);

        return [
            'url' => url(Storage::url($path))
        ];
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
                $controlador = new StudentsController();
                return($controlador->friendship());
            } else if($user_type == 2) {
                $controlador = new MentorsController();
                return($controlador->friendship());
            }
        }else {
            return view('users.close');
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
                $controlador = new StudentsController();
                return ($controlador->actual_friends());
            }else if ($user_type == 2){
                $controlador = new MentorsController();
                return($controlador->actual_friends());
            }
        } else {
            return view('users.close');
        }
    }

    /**
     * @param {Datos necesarios para crear una tupla de reportes (identificador del usuario reportado, razón del reporte)} request
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay usuario logueado y no se guarda la tupla correctamente devolvemos false como respuesta ajax}
     *         {si hay usuario logueado y se guarda la tupla correctamente devolvemos ture como respuesta ajax}
     */
    public function create_report(Request $request){
        if (!Auth::check()){
            return view('user.close');
        }

        $nueva_tupla = new Report_request();

        $nueva_tupla->reported = $request->id_reported;
        $nueva_tupla->reporter = Auth::user()->id     ;
        $nueva_tupla->reason   = $request->reason     ;
        $nueva_tupla->seen     = 0                    ;

        try {
            $nueva_tupla->save();
        } catch(Exception $e){
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }

//--Tutorías y novedades----------------------------------------------------------------------------
    /**
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay usuario logueado devolvemos la vista de los tutoriales}
     */
    public function tutorial(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.tutorial');
    }

    /**
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay usuario logueado devolvemos la vista de las novedades}
     */
    public function news(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.news');
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

        if (self::cifrate_private_key($request->actual_pass) !== Auth::user()->PASSWORD){
            return response()->json(['success' => false]);
        }

        $user = User::find(Auth::user()->id);

        $user->password = self::cifrate_private_key($request->nueva_pass);
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

    public function delete_store(Request $request){
        if (Auth::check()){
            $user = User::findOrFail(Auth::user()->id);
            $user->delete();
            return response()->json(['success' => true]);
        }else {
            return response()->json(['success' => false]);
        }
    }

    public function check_password_store(Request $request){
        $ret_resultado = false;
        if (Auth::check()){
            $id = Auth::user()->id;
            $usuario_autenticado = User::find($id);

            $clave_cifrada = self::cifrate_private_key($request->password);
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
        $user->banned      = 0;

        return $user;
    }

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

    private function CreateStudyRoom($param_mentor_id) {
        $study_room = new Study_room();

        $study_room->mentor_id = $param_mentor_id;
        $study_room->color     = 'Blue';

        $study_room->save();
    }

    private function CreateSynchronousMessage($param_study_room_id, $param_study_room_acces_id, $param_message){
        $sync_message = new Synchronous_message();

        $sync_message->study_room_id       = $param_study_room_id      ;
        $sync_message->study_room_acces_id = $param_study_room_acces_id;
        $sync_message->sender              = Auth::user()->id          ;
        $sync_message->message             = $param_message            ;

        if (Auth::user()->USER_TYPE == 1){
            $sync_message->seen_by_student = 1;
        } else if (Auth::user()->USER_TYPE == 2) {
            $sync_message->seen_by_mentor = 1;
        }

        $sync_message->save();
    }

    private function CreateTask($param_study_room_id, $param_titulo, $param_descripcion, $param_fecha_hasta, $param_fecha_creacion){
        $task = new Task();

        $task->study_room_id = $param_study_room_id ;
        $task->task_title    = $param_titulo        ;
        $task->description   = $param_descripcion   ;
        $task->last_day      = $param_fecha_hasta   ;
        $task->created_at    = $param_fecha_creacion;

        $task->save();

        if (Auth::user()->USER_TYPE == 2){
            //Buscamos los estudiantes de la sala de estudios
            $studentIds = DB::table('study_room_access')
                            ->where('STUDY_ROOM_ID', '=', Auth::user()->id)
                            ->select('STUDENT_ID')
                            ->get();

            foreach($studentIds as $id) {
                $nuevo_nodo = new Seen_task();

                $nuevo_nodo->task_id = $task->id;
                $nuevo_nodo->user_id = $id->STUDENT_ID;
                $nuevo_nodo->seen_task = 0;

                $nuevo_nodo->save();
            }
        }

    }

    private function CreateTutoring($param_study_room_id, $param_study_room_acces_id, $param_date, $param_status){
        $tutoring = new Tutoring();

        $tutoring->study_room_id       = $param_study_room_id;
        $tutoring->study_room_acces_id = $param_study_room_acces_id;
        $tutoring->date                = $param_date;
        $tutoring->status              = $param_status;
        $tutoring->seen_by_student      = 1;

        $tutoring->save();
    }

    private function CreateAnswer($param_task_id, $param_study_room_acces_id, $param_name) {
        $answer = new Answer();

        $answer->task_id             = $param_task_id;
        $answer->study_room_acces_id = $param_study_room_acces_id;
        $answer->name                = $param_name;

        $answer->save();
    }

    private function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }

    public function decrypt_info_store (Request $request){
        $key  = 'clave_de_cifrado_de_32_caracteres';
        $message = openssl_decrypt($request->message, 'aes-256-ecb', $key);

        return response()->json(['success' => true,
                                 'message'   => $message]);
    }
//--Gestión de las notificaciones-------------------------------------------------------------------
    /**
     * Todas las funciones NotSeen... hacen una búsqueda para comprobar si el usuario logueado tiene
     * información sin ver. Usada para saber si poner o no los puntos de notificación
     *
     * @return {En caso de que tengan algún dato sin ver} true
     *         {En caso de que no tenga datos sin ver} false
     */
    private function NotSeenReportRequest(){
        $ret_cantidad = 0;
        if (Auth::user()->USER_TYPE == 3){
            $ret_cantidad = Report_request::where('seen', '=', 0)
                                           ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    private function NotSeenFriendRequests(){
        $ret_cantidad = 0;
        if (Auth::user()->USER_TYPE == 1){
            $ret_cantidad = Friend_request::where('student_id', '=', Auth::user()->id)
                                           ->where('seen_by_student', '=', 0)
                                           ->count();

        } else if (Auth::user()->USER_TYPE == 2){
            $ret_cantidad = Friend_request::where('mentor_id', '=', Auth::user()->id)
                                           ->where('seen_by_mentor', '=', 0)
                                           ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    private function NotSeenSynchronousMessages(){
        $ret_cantidad = 0;

        if (Auth::user()->USER_TYPE == 1){
            $ret_cantidad = Synchronous_message::where('study_room_acces_id', '=', Auth::user()->id)
                                                ->where('seen_by_student', '=', 0)
                                                ->count();
        } else if (Auth::user()->USER_TYPE == 2){
            $ret_cantidad = Synchronous_message::where('study_room_id', '=', Auth::user()->id)
                                                ->where('seen_by_mentor', '=', 0)
                                                ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    private function NotSeenTutoring(){
        $ret_cantidad = 0;

        if (Auth::user()->USER_TYPE == 1){
            $ret_cantidad = Tutoring::where('study_room_acces_id', '=', Auth::user()->id)
                                     ->where('seen_by_student', '=', 0)
                                     ->count();

        } else if (Auth::user()->USER_TYPE == 2){
            $ret_cantidad = Tutoring::where('study_room_id', '=', Auth::user()->id)
                                     ->where('seen_by_mentor', '=', 0)
                                     ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    private function NotSeenTasks(){
        $ret_cantidad = 0;

        if (Auth::user()->USER_TYPE == 1){
            $ret_cantidad = Seen_task::where('user_id', '=', Auth::user()->id)
            ->where('seen_task', '=', 0)
            ->count();
        } else if (Auth::user()->USER_TYPE == 2){
            $ret_cantidad = Seen_task::where('user_id', '=', Auth::user()->id)
            ->where('seen_task', '=', 0)
            ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    private function NotSeenAnswers(){
        $ret_cantidad = 0;

        if (Auth::user()->USER_TYPE == 2){
            $ret_cantidad = DB::table('answers')
                               ->join('tasks', 'answers.TASK_ID', '=', 'tasks.id')
                               ->where('tasks.STUDY_ROOM_ID', '=', 2)
                               ->where('answers.SEEN_BY_MENTOR', '=', 0)
                               ->count();
        }

        if ($ret_cantidad > 0){
            return true;
        }

        return false;
    }

    /**
     * Todas las funciones ...Saw actualiza el dato que marca de si una tupla está vista o no a 1 para indicar
     * que está visualizada.
     *
     * @return {Si no hay usuario logueado devolvemos la vista de sesión cerrada}
     *         {Si hay usuario logueado devolvemos true en caso de que la tupla se actualice correctamente}
     */
    public function ReportRequestSaw(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        Report_request::where('seen', '=', 0)
                      ->update(['seen' => 1]);

        return response()->json(['success' => true]);
    }

    public function FriendRequestsSaw(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1){
            Friend_request::where('seen_by_student', '=', 0)
                          ->update(['seen_by_student' => 1]);
        } else if (Auth::user()->USER_TYPE == 2) {
            Friend_request::where('seen_by_mentor', '=', 0)
                          ->update(['seen_by_mentor' => 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param {Identificador de la sala de estudios sobre la que tenemos que realizar el cambio} id_user
     */
    private function SynchronousMessagesSaw($id_user){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1) {
            Synchronous_message::where('seen_by_student', '=', 0)
                               ->where('study_room_id', '=', $id_user)
                               ->update(['seen_by_student' => 1]);
        }else if(Auth::user()->USER_TYPE == 2) {
            Synchronous_message::where('seen_by_mentor', '=', 0)
                               ->where('study_room_acces_id', '=', $id_user)
                               ->update(['seen_by_mentor' => 1]);
        }

        return response()->json(['success' => true]);
    }

    public function TutoringSaw(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1) {
            Tutoring::where('seen_by_student', '=', 0)
                    ->update(['seen_by_student' => 1]);
        }else if(Auth::user()->USER_TYPE == 2) {
            Tutoring::where('seen_by_mentor', '=', 0)
                    ->update(['seen_by_mentor' => 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param {Identificador de la tarea sobre la que queremos modificar el marcador de visualización} request->id_tarea
     */
    public function TutoringModificationsNotification(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 1) {
            Tutoring::where('id', '=', $request->id_tarea)
                    ->update(['seen_by_mentor' => 0]);
        }else if(Auth::user()->USER_TYPE == 2) {
            Tutoring::where('id', '=', $request->id_tarea)
                    ->update(['seen_by_student' => 0]);
        }

        return response()->json(['success' => true]);
    }

    public function TasksSaw(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        Seen_task::where('SEEN_TASK', '=', 0)
                 ->where('USER_ID', '=', Auth::user()->id)
                 ->update(['SEEN_TASK' => 1]);

        return response()->json(['success' => true]);
    }

    public function AnswersSaw(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        if (Auth::user()->USER_TYPE == 2) {
            Answer::where('seen_by_mentor', '=', 0)
                  ->update(['seen_by_mentor' => 1]);
        }

        return response()->json(['success' => true]);
    }
//--------------------------------------------------------------------------------------------------
}
