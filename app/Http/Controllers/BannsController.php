<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Exception;

use App\Models\User;
use App\Models\Report_request;

use App\DataTables\Report_requestDataTable;
use App\DataTables\UsersDataTable;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-06-21 16:23:23
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-21 16:35:36
 * @Description: Controlador encargado de las funcionalidades asociadas al reporte y banneo de usaurio.
 */

class BannsController extends Controller
{
//--Mostrar las vistas de bloqueo-------------------------------------------------------------------
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

//--Función para bloequear usuarios-----------------------------------------------------------------
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

//--Función para reportar usuarios------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
}
