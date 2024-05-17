<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:20:21
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-17 14:21:25
 * @Description: Vista asociada a la opción de menú Primeros Pasos del apartado Guías de uso para el tipo de usuairo administrador.
 */
-->
@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <!--Linkeamos los estilos-->
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section ('main')
<div class='PanelTaskBoardPrincipal'>

    <div class='PanelTaskBoardTareas' id='PanelTaskBoardTareas'>
        <!--Parte superior de la web titulo + btn nueva tarea mentores-->
        <div class='PanelTaskBoardSuperiorMentor' id='PanelTaskBoardSuperiorMentor'>
            <h2>Tutoriales y Primeros Pasos:</h2>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Primer paso:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>General:</p>
                <p>
                    En esta aplicación hay dos roles principales, puedes ser mentor o estudiante y según el rol que desempeñes dentro de la aplicación
                    podrás acceder a unas funcionalidades u otras. Hay algunas funciones que son comunes para ambos roles.
                </p>
                <p class='TaskTitulo'>Mentores:</p>
                <p>
                    Los mentores desde el primer inicio de sesión pueden crear tareas pero tendrán el resto de opciones inaccesibles hasta que tengan
                    integrantes en su sala. Una vez reciba solicitudes de amistad de alumnos que quieren acceder a su sala podrán aceptarlas o rechazaras,
                    podrán aceptar hasta un máximo de 5 alumnos. Una vez tengan un alumno en su sala de estudios podrán acceder a la mayoría de opciones de esta.
                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>
                    Al iniciar sesión por primera vez solo podrán acceder a la búsqueda de mentores para solicitarles acceder a su sala de estudios. El resto de
                    las opciones serán inaccesibles hasta que estén dentro de una sala de estudios.
                </p>
            </div>
        </div>

    </div>
</div>

@endsection
