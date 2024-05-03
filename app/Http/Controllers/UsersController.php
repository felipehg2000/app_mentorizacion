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
 * @Last Modified time: 2024-05-03 09:23:41
 * @Description: En este controlador nos encargaremos de gestionar las diferentes rutas de la parte de usuarios. Las funciones simples se encargarán de mostrar las vistas principales y
 *               las funciones acabadas en store se encargarán de la gestión de datos, tanto del alta, como consulta o modificación de los datos. Tendremos que gestionar las contraseñas,
 *               encriptandolas y gestionando hashes para controlar que no se hayan corrompido las tuplas.
 * @Problems: los modelos tienen una diferenciación entre mayusculas y minusculas, al estar los atributos de la base de datos en mayusculas tengo que hacer las comprobaciones en mayusculas
 *            porque si no no estoy cogiendo los datos correctamente
 */


class UsersController extends Controller
{
//--------------------------------------------------------------------------------------------------
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

    public function admin_tut(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.tutorial');
    }

    public function admin_news(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.news');
    }

    public function create_admin(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('admins.create');
    }

    public function create_admin_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $new_admin = new User();

        $new_admin->name        = $request->nombre   ;
        $new_admin->surname     = $request->apellidos;
        $new_admin->email       = $request->email    ;
        $new_admin->user        = $request->usuario  ;
        $new_admin->password    = self::cifrate_private_key ($request->password);
        $new_admin->user_type   = 3;
        $new_admin->study_area  = 0;
        $new_admin->description = $request->description;
        $new_admin->banned      = 0;

        $new_admin->save();

        return response()->json(['success' => true]);
    }

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

    public function modify_admin_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        $admin = User::find(Auth::user()->id);

        if ($admin){
            $admin->name        = $request->nombre   ;
            $admin->surname     = $request->apellidos;
            $admin->email       = $request->email    ;
            $admin->user        = $request->usuario  ;
            if ($request->password != ""){
                $admin->password    = self::cifrate_private_key ($request->password);
            }
            $admin->description = $request->description;

            $admin->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

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

//--------------------------------------------------------------------------------------------------
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

//--------------------------------------------------------------------------------------------------
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
            } else if ($tipo_usu == 2) { //Mentor
                $id_sala_estudio = Auth::user()->id;
            }

            $tasks = DB::table('TASKS')
                        ->where('study_room_id', $id_sala_estudio)
                        ->orderBy('last_day', 'desc')
                        ->get();

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
     * Borra un registro de la base de datos, el que esté en $request->id_task
     */
    public function delete_task_store(Request $request){
        if (Auth::check()) {
            $task_id = $request->datos;
            $tarea = Task::where('id', $task_id)->first();
            $tarea->delete();

            return response()->json(['success' => true]);
        } else {
            return view('users.close');
        }
    }
//--------------------------------------------------------------------------------------------------
    /**
     * Mentor    : le mostrará las tareas cuyas fechas de último día ha pasado ya.
     * Estudiante: le mostrará sus tareas que aún no tienen una respuesta asociada
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
     * Mentor    : le mostrará las tareas cuyas fechas de último día aún no ha pasado.
     * Estudiante: le mostrará las tareas que tienen asociada una entrega
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

    public function found_task_store(Request $request){
        $task = Task::find($request->id);

        return response()->json(['success' => true,
                                 'tarea'   => $task]);
    }

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

//--------------------------------------------------------------------------------------------------
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
                'mensaje'    => $this->cifrate_private_key($request->datos['message']),
                'mi_id'      => Auth::user()->id          ,
                'message_id' => $id_mensaje
            ];
            Event::dispatch(new NewMessageEvent($message, $id_canal));

            return response()->json(['success' => true,
                                     'mi_id'   => $mi_id]);

        }else{
            return response()->json(['success' => false]);
        }
    }
//--------------------------------------------------------------------------------------------------
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

    public function get_tuto_data_store(Request $request){
        if (!Auth::check()){
            return response()->json(['success' => false]);
        }

        $datos_tutoria = Tutoring::find($request->id);

        return response()->json(['success'   => true,
                                 'user_type' => Auth::user()->USER_TYPE,
                                 'tut_data'  => $datos_tutoria]);
    }

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

//--------------------------------------------------------------------------------------------------
    public function tut_access(){
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

        Event::dispatch(new TutUpdateEvent($this->cifrate_private_key($request->texto), $id_canal));
    }

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

//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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

    public function create_report(Request $request){
        if (!Auth::check()){
            return view('user.close');
        }

        $nueva_tupla = new Report_request();

        $nueva_tupla->reported = $request->id_reported;
        $nueva_tupla->reporter = Auth::user()->id     ;
        $nueva_tupla->reason   = $request->reason     ;
        $nueva_tupla->seen     = 0                    ;

        $nueva_tupla->save();

        return response()->json(['success' => true]);
    }

//--------------------------------------------------------------------------------------------------
    public function tutorial(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.tutorial');
    }

    public function news(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.news');
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
        log(Auth::check());
        return view('users.create');
    }

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
//--------------------------------------------------------------------------------------------------
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

    public function modify_password(){
        if (!Auth::check()){
            return view('users.close');
        }

        return view('users.modify_password');
    }

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
        $ruta_archivo = public_path('photos/my_image.JPG');
        if (File::exists($ruta_archivo)) {
            File::delete($ruta_archivo);
        }

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
            'rep_password'  => [           'min:5', 'required'                ],

            'campoestudio'  => ['not_in:0'],
            'tipousuario'   => ['not_in:0']

        ]);
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
        $user->banned      = 0;

        return $user;
    }
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
    private function CreateStudyRoom($param_mentor_id) {
        $study_room = new Study_room();

        $study_room->mentor_id = $param_mentor_id;
        $study_room->color     = 'Blue';

        $study_room->save();
    }
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
    private function CreateTutoring($param_study_room_id, $param_study_room_acces_id, $param_date, $param_status){
        $tutoring = new Tutoring();

        $tutoring->study_room_id       = $param_study_room_id;
        $tutoring->study_room_acces_id = $param_study_room_acces_id;
        $tutoring->date                = $param_date;
        $tutoring->status              = $param_status;
        $tutoring->seen_by_student      = 1;

        $tutoring->save();
    }
//--------------------------------------------------------------------------------------------------
    private function CreateAnswer($param_task_id, $param_study_room_acces_id, $param_name) {
        $answer = new Answer();

        $answer->task_id             = $param_task_id;
        $answer->study_room_acces_id = $param_study_room_acces_id;
        $answer->name                = $param_name;

        $answer->save();
    }
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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

        if (Auth::user()->USER_TYPE == 1) {
            Answer::where('seen_by_mentor', '=', 0)
                  ->update(['seen_by_mentor' => 1]);
        }

        return response()->json(['success' => true]);
    }
//--------------------------------------------------------------------------------------------------
}
