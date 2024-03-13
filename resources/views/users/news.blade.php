@extends('layouts.plantillaUsuLogeado')

@section('title', 'Novedades')

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
            <h2>Novedades por versión:</h2>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Versión 1:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p>
                    Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto.
                    Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500,
                    cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó
                    una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.
                    No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos,
                    quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset",
                    las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como
                    por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
