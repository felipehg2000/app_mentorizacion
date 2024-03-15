@extends('layouts.plantillaUsuLogeado')

@if ($tipo_usu == 1)
    @section('title', 'Estudiantes')
@elseif($tipo_usu == 2)
    @section('title', 'Mentor')
@endif

@section ('style')
    <!--Linkeamos los estilos-->
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <!--Definición de rutas-->
    <script>
        var url_add_task    = "{{ route('users.add_task.store'   ) }}";
        var url_update_task = "{{ route('users.update_task.store') }}";
        var url_delete_task = "{{ route('users.delete_task.store') }}";
        var url_update_file = "{{ route('users.task_board.store' ) }}";
    </script>
    <script src="{{ asset('js/User/task_boardUserScript.js') }}"></script>
@endsection

@section ('main')
    <div class='PanelTaskBoardPrincipal'>

        <div class='PanelTaskBoardTareas' id='PanelTaskBoardTareas'>
            <!--Parte superior de la web titulo + btn nueva tarea mentores-->
            <div class='PanelTaskBoardSuperiorMentor' id='PanelTaskBoardSuperiorMentor'>
                <h2>Tablón de anuncios:</h2>
            </div>

            @if($tipo_usu == 2) <!--MENTOR-->
                <div classs='PanelTaskBoardEspecificoMentor'>
                    <button class='btn_create_task' type="submit" onclick="MostrarPanelFormulario()">
                        <i class="fa fa-plus" style="font-size:16px;color:white;margin-left: -2px">  Añadir nueva tarea</i>
                        </button>
                </div>
            @endif

            <!--Cargamos los paneles de las tareas que se vayan a mostrar-->
            @if($tasks != NULL)
            @foreach ($tasks as $task)
                <div class='PanelNewTask'>
                    @if(\Carbon\Carbon::parse($task->LAST_DAY)->gt(\Carbon\Carbon::now()))
                        <!--Fecha es mayor que hoy-->
                        <div class='TaskTitulo_Azul'>
                    @else
                        <!--Fecha es menor o igual que hoy-->
                        <div class='TaskTitulo_Rojo'>
                    @endif
                        <!--Hacemos el cast a data table y lo formateamos para que salga solo la fecha y no salga fecha y hora-->
                        <p id='fecha_tarea_{{ $task->id }}' class='tituloEmergente'>{{ \Carbon\Carbon::parse($task->LAST_DAY)->format('d-m-Y')}}</p>
                    </div>

                    <div class='PanelFormNewTask'>
                        <p class='TaskTitulo'>Titulo de la tarea:</p>
                        <p id='titulo_tarea_{{ $task->id }}' class='TaskInfo'>{{ $task->TASK_TITLE }}</p><br>
                        <p class='TaskTitulo'>Descripción</p>
                        <p id='descripcion_tarea_{{ $task->id }}' class='TaskInfo'>{{ $task->DESCRIPTION }}</p><br>
                    </div>

                    <div class='PanelBotones'>
                        @if ($tipo_usu == 1) <!--Estudiante-->
                            <button class='btn_create' type="submit" onclick="VerDatosEspecíficos({{ $tipo_usu }}, {{ $task->id }})">Realizar entrega</button>
                        @elseif($tipo_usu == 2)<!--Mentor-->
                            <button class='btn_create_multiple' type="submit" onclick="VerDatosEspecíficos({{ $tipo_usu }}, {{ $task->id }})">Modificar</button>
                            <button class='btnEmergenteAceptarDelete' type="submit" onclick="BorrarTarea({{ $task->id }})">Eliminar</button>
                        @endif
                    </div>
                </div>
            @endforeach
            @endif

        <div>

        <!--Panel para crear nuevas tareas-->
        <div class='PanelTaskBoardCreateTask' id='PanelTaskBoardCreateTask'>
            <div class='PanelNewTask'>
                <div class='pnlEmergenteTitulo'>
                    <i class="fa fa-plus" style="font-size:24px;color:white"></i>
                    <p class="tituloEmergente">  Añadir</p>
                    <p id='id_task' style="visibility: hidden">Texto oculto</p>
                </div>

                <div class='PanelFormNewTask'>
                    <br><br>
                    <label for='input_name'>Titulo</label><br>
                    <input id='input_name' type='text' placeholder="Titulo">

                    <label for='input_last_day'>Último día</label><br>
                    <input id='input_last_day' type='date' placeholder="Fecha">

                    <label for='input_description'>Descripción</label><br>
                    <textarea id='input_description' type='text' placeholder="Descripción"></textarea>

                    @if($tipo_usu == 1) <!--ESTUDIANTE-->
                    <label for='input_upload'>Realizar entrega</label>
                    <input id='input_upload' type="file">
                    @endif
                </div>

                <div class="PanelBotones">
                    @if ($tipo_usu == 1) <!--ESTUDIANTE-->
                        <button class='btn_create_multiple' type="submit" onclick="CrearNuevaRespuesta()">Guardar</button>
                    @elseif($tipo_usu == 2) <!--MENTOR-->
                        <button class='btn_create_multiple' type="submit" onclick="CrearNuevaTarea()">Guardar</button>
                    @endif
                    <button class='btnEmergenteAceptarDelete' type="submit" onclick="MostrarPanelTareas()">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
@endsection
