@extends('layouts.plantillaUsuLogeado')

@section('title', 'Estudiantes')

@section ('style')
    <link href="{{ asset('css/task_boardStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_add_task = "{{ route('users.add_task.store') }}";
    </script>
    <script src="{{ asset('js/User/task_boardUserScript.js') }}"></script>
@endsection

@section ('main')
    <div class='PanelTaskBoardPrincipal'>

        <div class='PanelTaskBoardTareas' id='PanelTaskBoardTareas'>
            <div class='PanelTaskBoardSuperiorMentor' id='PanelTaskBoardSuperiorMentor'>
                <h2>Tablón de anuncios:</h2><br>
                @if($tipo_usu == 2) <!--MENTOR-->
                    <div classs='PanelTaskBoardEspecificoMentor'>
                        <button class='btn_create' type="submit" onclick="MostrarPanelFormulario()">
                            <i class="fa fa-plus" style="font-size:16px;color:white;margin-left: -2px"></i>
                         </button>
                         <p>Nueva tarea<p>
                    </div>
                @endif
            </div>


                <div class='PanelNewTask'>
                    <div class='PanelTituloTask_n'>
                        <p class='LabelTituloTask_n'>1<p>
                    </div>

                    <div class=''>

                    </div>

                    <div class=''>
                        <button class='' type="submit" onclick="VerDatosEspecíficos()">Ver</button>
                    </div>
                </div>

        <div>

        <div class='PanelTaskBoardCreateTask' id='PanelTaskBoardCreateTask'>
            <div class='PanelNewTask'>
                <div class='PanelTituloNewTask'>
                    <i class="fa fa-plus" style="font-size:24px;color:white"></i>
                    <p class="tituloEmergente">  Añadir</p>
                </div>

                <div class='PanelFormNewTask'>
                    <br><br>
                    <label for='input_name'>Titulo</label><br>
                    <input id='input_name' type='text' placeholder="Titulo"><br>

                    <label for='input_last_day'>Último día</label><br>
                    <input id='input_last_day' type='date' placeholder="Fecha"><br>

                    <label for='input_description'>Descripción</label><br>
                    <textarea id='input_description' type='text' placeholder="Descripción"></textarea><br>
                </div>

                <div class="PanelBotones">
                    <button class='btn_create_multiple' type="submit" onclick="CrearNuevaTarea()">Guardar</button>
                    <button class='btnEmergenteAceptarDelete' type="submit" onclick="MostrarPanelTareas()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
