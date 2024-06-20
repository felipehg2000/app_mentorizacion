<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Answer;
use App\Models\Synchronous_message;
use App\Models\Tutoring;
use App\Models\Friend_request;
use App\Models\Seen_task;
use App\Models\Report_request;

class BrowseController extends Controller
{
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

//--Tutorías y novedades----------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------

}
