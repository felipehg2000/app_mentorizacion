<?php

namespace App\Http\Controllers;

use App\DataTables\TaskDataTable;
use App\DataTables\TutoringDataTable;
use App\Models\User;
use App\Models\Student;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\MentorsController;
use App\Models\Study_room;
use App\Models\Synchronous_message;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTime;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:13:31
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-03-12 00:46:14
 * @Description: En este controlador nos encargaremos de gestionar las diferentes rutas de la parte de usuarios. Las funciones simples se encargarán de mostrar las vistas principales y
 *               las funciones acabadas en store se encargarán de la gestión de datos, tanto del alta, como consulta o modificación de los datos. Tendremos que gestionar las contraseñas,
 *               encriptandolas y gestionando hashes para controlar que no se hayan corrompido las tuplas.
 * @Problems: los modelos tienen una diferenciación entre mayusculas y minusculas, al estar los atributos de la base de datos en mayusculas tengo que hacer las comprobaciones en mayusculas
 *            porque si no no estoy cogiendo los datos correctamente
 */


class UsersController extends Controller
{
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

    /*public function task_board_store(Request $request){

    }*/

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
                                ->whereDate('LAST_DAY', '<', now());
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

        /*
         Todos los usuarios que han entregado algo
         SELECT
            users.id  ,
            users.NAME,
            users.SURNAME
         FROM
            study_rooms,
            study_room_access,
            tasks,
            answers,
            users
         WHERE
            study_rooms.MENTOR_ID = 1								AND
            study_rooms.MENTOR_ID = study_room_access.STUDY_ROOM_ID AND
            study_room_access.LOGIC_CANCEL = 0						AND
            tasks.STUDY_ROOM_ID = study_rooms.MENTOR_ID				AND
            tasks.id			= 7									AND
            answers.STUDY_ROOM_ACCES_ID = study_room_access.STUDENT_ID AND
            answers.TASK_ID		= 7										AND
            users.id = study_room_access.STUDENT_ID
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
                $mis_amigos = DB::table('USERS')
                                ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.MENTOR_ID', '=', 'USERS.ID')
                                ->where('FRIEND_REQUESTS.STUDENT_ID', '=', Auth::user()->id)
                                ->where('FRIEND_REQUESTS.STATUS', '=', 2)
                                ->select('USERS.*')
                                ->get();
            } else{
                $mis_amigos = DB::table('USERS')
                                ->join('FRIEND_REQUESTS', 'FRIEND_REQUESTS.STUDENT_ID', '=', 'USERS.ID')
                                ->where('FRIEND_REQUESTS.MENTOR_ID', '=', Auth::user()->id)
                                ->where('FRIEND_REQUESTS.STATUS', '=', 2)
                                ->select('USERS.*')
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

            $resultado = DB::table  ('synchronous_messages')
                           ->join   ('study_rooms'      , 'synchronous_messages.STUDY_ROOM_ID', '=', 'study_rooms.mentor_id'          )
                           ->join   ('study_room_access', 'study_rooms.mentor_id'             , '=', 'study_room_access.STUDY_ROOM_ID')
                           ->where  ('study_rooms.MENTOR_ID'       , '=', $id_mentor    )
                           ->where  ('study_room_access.STUDENT_ID', '=', $id_estudiante)
                           ->select ('synchronous_messages.id', 'synchronous_messages.SENDER', 'synchronous_messages.MESSAGE')
                           ->orderBy('synchronous_messages.created_at', 'ASC')
                           ->limit(30)
                           ->get();

            $selected_user = DB::table ('users')
                               ->where ('users.id', '=', $request->contact_id)
                               ->select('NAME', 'SURNAME')
                               ->first();

            return response()->json(['success'    => true      ,
                                     'data'       => $resultado,
                                     'selec_user' => $selected_user]);

        }else{
            return response()->json(['success' => false]);
        }
    }

    public function send_message_store(Request $request){
        if (Auth::check()){
            $mi_id = Auth::user()->id;
            $id_estudiante = 0;
            $id_mentor     = 0;

            //Necesito el id del estudiante y del mentor en ambos casos
            if (Auth::user()->USER_TYPE == 1){ //Estudiante
                $id_estudiante = $mi_id                  ;
                $id_mentor     = $request->datos['id_chat'];

                $this->CreateSynchronousMessage($id_mentor, $id_estudiante, $request->datos['message']);
            }else if(Auth::user()->USER_TYPE == 2){ //Mentor
                $id_mentor     = $mi_id;
                $id_estudiante = $request->datos['id_chat'];


                $this->CreateSynchronousMessage($id_mentor, $id_estudiante, $request->datos['message']);
            }

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


                        $action_code = $action_code = ' <a onclick="ClickDataTable({{ $model->id }})">
                                                            <i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>
                                                        </a>';
                        return DataTables::of($query)
                                          ->addColumn('action', $action_code)
                                          ->rawColumns(['action'])
                                          ->toJson();
        }

        $tipo_usu = Auth::user()->USER_TYPE;

        return $dataTable->render('users.tut_request', compact('tipo_usu'));
    }

    public function add_tuto_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        //Insertar registro en la base de datos
    }

    public function get_tuto_data_store(Request $request){
        if (!Auth::check()){
            return view('users.close');
        }

        //Buscar datos en la base de datos
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
            log(0);
            $user_type = Auth::user()->USER_TYPE;
            if($user_type == 1){
                $controlador = new StudentsController();
                return ($controlador->actual_friends());
            }else if ($user_type == 2){
                $controlador = new MentorsController();
                return($controlador->actual_friends());
            }
        } else {
            log(1);
            return view('users.close');
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
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
    public function delete_store(Request $request){
        if (Auth::check()){
            $user = User::findOrFail(Auth::user()->id);
            $user->delete();
            return response()->json(['success' => true]);
        }else {
            return response()->json(['success' => false]);
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
    }
//--------------------------------------------------------------------------------------------------
    private function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }
//--------------------------------------------------------------------------------------------------
}
