@extends('layouts.plantillaAdminLoegeado')

@if ($tipo_usu == 1)
    @section('title', 'Estudiantes')
@elseif($tipo_usu == 2)
    @section('title', 'Mentor')
@elseif($tipo_usu == 3)
    @section('title', 'Admin')
@endif

@section('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimpleStyle.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_modify_img_store = "{{ route('users.modify_img_perf.store') }}"
    </script>
    <script src="{{ asset('js/User/change_img_perf.js') }}"></script>
@endsection

@section ('main')
    <div class="pnlPrincipalFriendship">
        @for($i = 1; $i < 7; $i++)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/Perfiles/img_perf_' . strval($i) . '.JPG')}}">
                </div>
                <br>
                <div class="PanelBotones">
                    <button type="submit" class="btn_create_multiple" id="img_perf_{{$i}}.JPG" onclick="ImagenSeleccionada({{$i}})">Seleccionar</button>
                </div>
            </div>
        @endfor
        <p id='tipo_img' style="visibility: hidden">{{ $tipo_img }}</p>
    </div>
@endsection
