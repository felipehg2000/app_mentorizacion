<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use App\DataTables\TutoringDataTable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use DateTime;

use App\Models\Tutoring;

use App\Events\TutUpdateEvent;

class TutorshipsController extends Controller
{
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

    /**
     * Crea una entrada para la tabla Tutorings con los datos parametrizados
     *
     * @param {Identificador del mentor de la tutría que vamos a crear} param_study_room_id
     *        {Identificador del estudiante de la tutría que vamos a crear} param_study_room_acces_id
     *        {Fecha y hora  de la tutría que vamos a crear} param_date
     *        {Estado de la tutría que vamos a crear} param_status
     */
    private function CreateTutoring($param_study_room_id, $param_study_room_acces_id, $param_date, $param_status){
        $tutoring = new Tutoring();

        $tutoring->study_room_id       = $param_study_room_id;
        $tutoring->study_room_acces_id = $param_study_room_acces_id;
        $tutoring->date                = $param_date;
        $tutoring->status              = $param_status;
        $tutoring->seen_by_student      = 1;

        $tutoring->save();
    }
}
