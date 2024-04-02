@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/tut_accessStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/tut_acessScript.js') }}"></script>
    <script>
        var url_send_text = "{{ route('users.send_text.store') }}"
        var url_close     = "{{ route('users.close') }}";
    </script>
@endsection

@if ($tipo_usu == 1)
    @section('title', 'Estudiantes')
@elseif($tipo_usu == 2)
    @section('title', 'Mentor')
@endif

@section ('main')
    <p id='tipo_usu' style="visibility: hidden">{{ $tipo_usu }}</p>
    <p id='id_tuto'  style="visibility: hidden">{{ $id_tuto  }}</p>
    <p id='id_user'  style="visibility: hidden">{{ $id_user  }}</p>

    <div class='pnlCopmpletoTut'>
        <div class='pnlSupTut'>
            <h2 id='tit_tut_access'> {{ $titulo }}</h2>
            <button class='btn_create' id='btnPizarra' onclick="ActivarPizarraClick()">Activar Pizarra</button>
        </div>

        <div class='pnlPrinTut'>
            <div class='pnlIzqTut'>
                <textarea class='tuto' id='textAreaMentor' onkeydown="MentorPulsaTecla()"></textarea>
            </div>

            <div class='pnlDchaTut'>
                <textarea class='tuto' id='textAreaEstudiante' onkeydown="EstudiantePulsaTecla()"></textarea>
            </div>
        </div>

        <div class='pnlPizarra' id='pnlPizarra'>

        </div>

    </div>
@endsection
