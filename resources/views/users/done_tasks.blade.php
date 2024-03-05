@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/done_tasksStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/done_tasksScript.js') }}"></script>
    <script>
        var url_found_task = "{{ route('users.found_task.store') }}";
    </script>
@endsection

@section ('main')
    <div class="container">
        <div class="card">
            <div class="card-header">Resumen de tareas</div>
            <div class="card-body">
                {{ $dataTable->table() }}

            </div>
        </div>
    </div>

    <div class='PanelShowData' id='PanelShowData'>
        <div class='pnlEmergenteTitulo' id='PanelTituloE'>
            <i class="fa fa-plus" style="font-size:24px;color:white"></i>
            <p class="tituloEmergente">  Agregar entrega</p>
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

            <label for='input_upload' id='lbl_input_upload'>Realizar entrega</label>
            <input id='input_upload' type="file">
        </div>

        <div class="PanelBotones">
            <button class='btn_create_multiple' type="submit" onclick="CrearNuevaRespuesta()">Guardar</button>
            <button class='btnEmergenteAceptarDelete' type="submit" onclick="MostrarPanelTareas()">Cancelar</button>
        </div>
    </div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
