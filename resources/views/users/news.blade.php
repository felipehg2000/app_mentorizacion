@extends('layouts.plantillaUsuLogeado')

@section('title', 'Novedades')

@section ('style')
    <!--Linkeamos los estilos-->
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
@endsection


@section ('main')
<div class='PanelTaskBoardTareas'
    <div class='PanelNewTask'>
        <div class='TaskTitulo_Azul'>
            <p iclass='tituloEmergente'>Versión 1:</p>
        </div>

        <div class='PanelFormNewTask'>
            <p class='TaskTitulo'></p>
            <p>
                Ya que todo es nuevo, para empezar a utilizar la aplicación accede a la opción de primeros pasos y lee los tutoriales, así podrás comenzar a utilizar la aplicación de la mejor manera posible.
            </p>
        </div>
    </div>
</div>
@endsection
