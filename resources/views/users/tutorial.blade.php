@extends('layouts.plantillaUsuLogeado')

@section('title', 'Primeros pasos')

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
                <p class='TaskTitulo'>Mentores:</p>
                <p>
                    Los mentores podrán tener hasta 5 alumnos en su sala de estudios.
                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>
                    Para comenzar a utilizar la aplicación, en caso de que sea estudiante debe seguir a un tutor y entrar en su sala de estudio. Solo podrá estar en una sala de estudio aunque se podrá salir cuando lo desee.
                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Tareas:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Mentores:</p>
                <p>

                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>

                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Chats:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Mentores:</p>
                <p>

                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>

                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Gestión de usuario:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p>

                </p>
            </div>
        </div>


    </div>
</div>
@endsection
