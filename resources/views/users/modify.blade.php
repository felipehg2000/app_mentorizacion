@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}"
@endsection
@section('title', 'Modificar datos')

@section('js')
    <script>
        var url_modify_store = "{{route('users.modify.store')}}";
    </script>
    <script src="{{ asset('js/User/modifyUserScript.js') }}"></script>
@endsection

@section ('main')
<main>
    <div class="pnlSuperiorEspecifico">
        <h1 class="titulo">Modificar datos</h1>
    </div>
    <!--<form action="{{route('users.modify.store')}}" method="POST" id='formModify'>-->
    <form id='formModify'>
        @csrf
        <div class= "pnlInferiorEspecifico">
            <h2 class="SubtituloApartado">Datos generales de los usuarios</h2>                  <br>

            <label for="name">Nombre:* </label>                                                          <br>
            <input type="text" id="name" name="name" value=" {{ $data['name'] }} ">             <br>

            <label for="surname">Apellidos: </label>                                                    <br>
            <input type="text" id="surname" name="surname" value=" {{ $data['surname'] }} ">    <br>

            <label for="email">Email:* </label><br>
            <input type="text" id="email" name="email" value=" {{ $data['email'] }} ">          <br>

            <label for="user">Usuario: </label><br>
            <input type="text" id="user" name="user" value=" {{ $data['user'] }} " disabled>             <br>

            <label for="tipousuario">Tipo de usuario: </label>                                                  <br>
            <select id="tipo_usuario" name="tipousuario" value=" {{ $data['tipousuario'] }} " disabled>
                <option value="1" {{ $data['tipousuario'] == 1 ? 'selected' : ''}}>Estudiante</option>
                <option value="2" {{ $data['tipousuario'] == 2 ? 'selected' : ''}}>Mentor</option>
            </select>                                                                           <br>

            <label for="campoestudio">Campo de estudio: </label><br>
            <select id="campo_estudio" name="campoestudio" value=" {{ $data['campoestudio'] }} ">
                <option value="1" {{ $data['campoestudio'] == 1 ? 'selected' : '' }}>Rama tecnológica</option>
                <option value="2" {{ $data['campoestudio'] == 2 ? 'selected' : '' }}>Rama biosanitaria</option>
                <option value="3" {{ $data['campoestudio'] == 3 ? 'selected' : '' }}>Rama de arte</option>
                <option value="4" {{ $data['campoestudio'] == 4 ? 'selected' : '' }}>Rama jurista</option>
                <option value="5" {{ $data['campoestudio'] == 5 ? 'selected' : '' }}>Rama lingüistica</option>
            </select>                                                                                  <br>

            <label for="description">Descripción: </label><br>
            <input type="text" id="description" name="description" value=" {{ $data['description'] }} "><br>

            @if ($data['tipousuario'] == 1)
                <h2>Datos especificos de los estudiantes:</h2><br>

                <label for="career">Estudios que cursas: </label><br>
                <input type="text" id="career" value="{{ $data['career'] }}">        <br>

                <label for="first_year">Año de comienzo: </label><br>
                <input type="number" min="1800" max="2024" id="first_year" value="{{ $data['first_year'] }}"> <br>

                <label for="duration">Duración: </label><br>
                <input type="number" min="1" max="10" id="duration" value="{{ $data['duration'] }}">   <br>
            @else
                <h2>Datos especificos de los mentores:<h2><br>

                <label for="company">Empresa: </label><br>
                <input type="text" id="company" value="{{ $data['company'] }}">        <br>

                <label for="job">Puesto: </label><br>
                <input type="text" id="job" value="{{ $data['job'] }}">
            @endif
        </div>
    </form>
    <div class="pnlInferiorEspecifico">
        <button type="submit" class="btn_create" onclick="abrirPnlEmergente()">Modificar datos</button> <br>
    </div>
</main>
@endsection
