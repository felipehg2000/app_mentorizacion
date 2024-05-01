<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Friend_request;

class StudentsController extends Controller{
    //--------------------------------------------------------------------------------------------------
    /**
     * FRIENDSHIP REDIRECTION
     * ======================
     * Guarda en una variable todos los mentores que sean del mismo campo de estudio que el estudiante y muestran la vista del estudiante de búsqueda de amigos mostrando
     * esos usuarios.
     *
     * TO DO: Tengo que quitar de esta vista los que ya aparezcan en la tabla de solicitudes de amistad.
     */
    public function friendship(){
        $user_type = 2;
        $users     = User::where('STUDY_AREA', Auth::user()->STUDY_AREA)
                         ->where('USER_TYPE' , $user_type              )
                         ->get();

        $titulo = '';
        if (!$users->isEmpty()){
            $titulo = 'Solicitudes de amistad:';
        }

        return view ('students.friendship', compact('users', 'titulo'));
    }
//----------------------------------------- ---------------------------------------------------------
    /**
     * FRIENDSHIP_STORE:
     * =================
     *
     * Recibe el nombre del usaurio con el que quiere contactar y se encarga de dar de alta en la base de datos la solicitud de amisrtad.
     */
    public function friendship_store(Request $request){
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
        return back();

    }
//--------------------------------------------------------------------------------------------------
    /**
     *  ACTUAL FREIENDS
     *  ===============
     * Función get : devuelve la vista que queremos ver (vista con todos los usuarios que se encuentran relacionados con el auth en la tabla FRIENDSHIP_REQUEST)
     * Función post: ¿No estoy seguro de si se necesita, para dejar de seguir a la gente por ejemplo?
     */
    public function actual_friends(){
        //Buscar los usuarios relacionados con nosotros
        $result_users = DB::table('users')
                           ->join('study_room_access', 'users.id', '=', 'study_room_access.STUDY_ROOM_ID')
                           ->where('study_room_access.STUDENT_ID', '=', Auth::user()->id)
                           ->where('study_room_access.LOGIC_CANCEL', '=', 0)
                           ->select('users.*')
                           ->get();
        return view('students.actual_friends', compact('result_users'));
    }
    public function actual_friends_store(Request $request){
        $student_id = Auth::user()->id;
        $mentor_id  = $request->id_user;

        DB::table('FRIEND_REQUESTS')
          ->where('STUDENT_ID', '=', $student_id)
          ->where('MENTOR_ID' , '=', $mentor_id )
          ->delete();

        DB::table('STUDY_ROOM_ACCESS')
          ->where('student_id', $student_id)
          ->update(['logic_cancel' => '1']);

        return response()->json(['success' => true]);
    }
//--------------------------------------------------------------------------------------------------
}
