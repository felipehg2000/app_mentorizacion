<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:22:53
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-17 14:22:53
 * @Description: Vista asociada a la opción de menú Novedades del tipo de usuario adminstrador
 */
-->
@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <!--Linkeamos los estilos-->
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/done_tasksScript.js') }}"></script>

@endsection

@section ('main')
<div class='PanelTaskBoardPrincipal'>

    <div class='PanelTaskBoardTareas' id='PanelTaskBoardTareas'>
        <!--Parte superior de la web titulo + btn nueva tarea mentores-->
        <div class='PanelTaskBoardSuperiorMentor' id='PanelTaskBoardSuperiorMentor'>
            <h2>Novedades por versión:</h2>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Versión 1:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p>
                    Al ser la primera versión, todas las novedades de la aplicación están explicadas en la opción "Primeros pasos",
                    en futuras versiones, o actualizaciones de la web, podrás encontrar en esta opción las diferencias respecto a las anteriores
                    versiones.
                </p>
            </div>
        </div>

    </div>
@endsection
