@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/tut_request.css'     ) }}" rel="stylesheet">
    <link href="{{ asset('css/done_tasksStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/tut_request.js') }}"></script>
    <script>
        var url_get_tuto    = "{{ route('users.get_tuto_data.store') }}";
        var url_add_tuto    = "{{ route('users.add_tuto.store'     ) }}";
        var url_close       = "{{ route('users.close'              ) }}";
        var url_update_tuto = "{{ route('users.update_tuto.store'  ) }}"
    </script>
@endsection

@if ($tipo_usu == 1)
    @section('title', 'Estudiantes')
@elseif($tipo_usu == 2)
    @section('title', 'Mentor')
@endif

@section ('main')
<div class="container">
    <div class="card">
        <div class="card-header"> Tutorías</div>
        <div class="card-body">
            @if($tipo_usu == 1)
                <button class="btn_create" type='submit' onclick="MostrarNewTutoring(1)">Solicitar</button>
            @endif

            {{ $dataTable->table() }}

        </div>
    </div>
</div>

<div class='new_tutoring' id='new_tutoring'>
    <div class='pnlEmergenteTitulo'>
        <p class="tituloEmergente">  Tutoría</p>
        <p id='id_tut' style="visibility: hidden">Texto oculto</p>
    </div>

    <div class='PanelFormNewTask'>
        <label for='input_fecha'>Día</label><br>
        <input id='input_fecha' type='date' placeholder="Fecha">

        <label for='input_hora'>Hora</label><br>
        <input id='input_hora' type='time' placeholder="Hora">

        <label for="input_estado" id="lbl_study_area">Estado</label>                                                                        <br>
        <select id="input_estado">
            <option value="0">En tratmite</option>
            <option value="1">Aceptada   </option>
            <option value="2">Denegada   </option>
        </select>
    </div>

    <div class='PanelBotones'>
        <button class='btn_create_multiple' type="submit" onclick="CrearOModificarNuevaTutoria()">Guardar</button>
        <button class='btnEmergenteAceptarDelete' type="submit" onclick="MostrarTabla()">Cancelar</button>
    </div>
</div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
