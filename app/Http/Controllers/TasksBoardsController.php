<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use DateTime;
use Carbon\Carbon;
use Exception;

use Illuminate\Support\Facades\Storage;

use App\Models\Task;
use App\Models\Answer;
use App\Models\Seen_task;

use App\DataTables\TaskDataTable;

class TasksBoardsController extends Controller
{
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
//--Funciones auxiliares----------------------------------------------------------------------------
    /**
     * Crea una entrada para la tabla TASKS con los datos parametrizados y una entrada por estudiante está en la sala de estudios para la
     * tabla SEEN_TASKS
     *
     * @param {Identificador de la tarea que vamos a crear} param_study_room_id
     *        {Título de la tarea que vamos a crear} param_titulo
     *        {Descripción de la tarea que vamos a crear} param_description
     *        {Fecha de finalización de la tarea que vamos a crear} param_fecha_hasta
     *        {Fecha de creación de la tarea que vamos a crear} param_fecha_creacion
     */
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

    /**
     * Crea una entrada para la tabla ANSWERS con los datos parametrizados
     *
     * @param {Identificador de la tarea asociada a la respuesta que vamos a crear} param_task_id
     *        {Identificador del estudiante asociado a la respuesta que vamos a crear} param_study_room_access_id
     *        {Nombre del archivo que se ha subido al storage asociado a la respuesta que vamos a crear} param_name
     */
    private function CreateAnswer($param_task_id, $param_study_room_acces_id, $param_name) {
        $answer = new Answer();

        $answer->task_id             = $param_task_id;
        $answer->study_room_acces_id = $param_study_room_acces_id;
        $answer->name                = $param_name;

        $answer->save();
    }
//--------------------------------------------------------------------------------------------------
}
