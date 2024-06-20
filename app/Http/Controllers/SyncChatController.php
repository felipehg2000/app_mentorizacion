<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Events\NewMessageEvent;
use Illuminate\Support\Facades\Event;

use App\Models\Synchronous_message;

class SyncChatController extends Controller
{
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
//--Funciones auxiliares----------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------

/**
     * Crea una entrada en la tabla SYNCHRONOUS_MESSAGES
     *
     * @param {Identificador del usuario mentor en la conversación} param_study_room_id
     *        {Identificador del usuario estudiante en la conversación} param_study_room_access_id
     *        {Mensaje mandado de un usuario a otro} param_message
     */
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

    /**
     * @param {Frase o palabra que queremos cifrar} clave
     * @return {Texto cifrado asociado a la clave que nos han pasado por parametro}
     */
    private function cifrate_private_key ($clave){
        $key  = 'clave_de_cifrado_de_32_caracteres';

        return openssl_encrypt($clave, 'aes-256-ecb', $key);
    }

    /**
     * @param {Texto o palabra que queremos descifrar} request->message
     * @return {Si no hay un usuario logueado devolveremos la vista de sesión cerrada}
     *         {Si hay un usuario logueado devolveremos true y el texto descifrado a la petición ajax}
     */
    public function decrypt_info_store (Request $request){
        if (!Auth::check()){
            return view('users.close');
        }
        $key  = 'clave_de_cifrado_de_32_caracteres';
        $message = openssl_decrypt($request->message, 'aes-256-ecb', $key);

        return response()->json(['success' => true,
                                 'message'   => $message]);
    }
}


