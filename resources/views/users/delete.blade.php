@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}"
@endsection
@section('title', 'Borrar datos')

@section('js')
    <script>
        var url_delete = "{{ route('users.delete.store') }}";
        var url_check  = "{{ route('users.check_password.store') }}";
        var url_home   = "{{ route('home')}}";
    </script>
    <script src="{{ asset('js/User/deleteUserScript.js') }}"></script>
@endsection

@section ('main')
<main>
    <main>
        <div class="pnlSuperiorEspecifico">
            <h1 class="titulo">Eliminar datos</h1>
        </div>
        <form action="{{route('users.delete.store')}}" method="POST">
            @csrf
            <div class= "pnlInferiorEspecifico">
                <h2 class="SubtituloApartado">Datos generales de los usuarios</h2>                  <br>

                <label for="name">Nombre: </label>                                                          <br>
                <input type="text" id="name" name="name" value=" {{ $data['name'] }} " disabled>             <br>

                <label for="surname">Apellidos: </label>                                                    <br>
                <input type="text" id="surname" name="surname" value=" {{ $data['surname'] }} " disabled>    <br>

                <label for="email">Email: </label><br>
                <input type="text" id="email" name="email" value=" {{ $data['email'] }} " disabled>          <br>

                <label for="user">Usuario: </label><br>
                <input type="text" id="user" name="user" value=" {{ $data['user'] }} " disabled>             <br>

                <label for="tipousuario">Tipo de usuario: </label>                                                  <br>
                <select id="tipo_usuario" name="tipousuario" value=" {{ $data['tipousuario'] }} " disabled>
                    <option value="1" {{ $data['tipousuario'] == 1 ? 'selected' : ''}}>Estudiante</option>
                    <option value="2" {{ $data['tipousuario'] == 2 ? 'selected' : ''}}>Mentor</option>
                </select>                                                                           <br>

                <label for="campoestudio">Campo de estudio: </label><br>
                <select id="campo_estudio" name="campoestudio" disabled>
                    <option value="1" {{ $data['campoestudio'] == 1 ? 'selected' : '' }}>Rama tecnológica</option>
                    <option value="2" {{ $data['campoestudio'] == 2 ? 'selected' : '' }}>Rama biosanitaria</option>
                    <option value="3" {{ $data['campoestudio'] == 3 ? 'selected' : '' }}>Rama de arte</option>
                    <option value="4" {{ $data['campoestudio'] == 4 ? 'selected' : '' }}>Rama jurista</option>
                    <option value="5" {{ $data['campoestudio'] == 5 ? 'selected' : '' }}>Rama lingüistica</option>
                </select>                                                                                  <br>

                <label for="description">Descripción: </label><br>
                <input type="text" id="description" name="description" value=" {{ $data['description'] }} " disabled><br>

                @if ($data['tipousuario'] == 1)
                    <h2>Datos especificos de los estudiantes:</h2><br>

                    <label for="career">Estudios que cursas: </label><br>
                    <input type="text" id="career" value="{{ $data['career'] }}" disabled>        <br>

                    <label for="first_year">Año de comienzo: </label><br>
                    <input type="numeric" id="first_year" value="{{ $data['first_year'] }}" disabled> <br>

                    <label for="duration">Duración: </label><br>
                    <input type="numeric" id="duration" value="{{ $data['duration'] }}" disabled>   <br>
                @else
                    <h2>Datos especificos de los mentores:<h2><br>

                    <label for="company">Empresa: </label><br>
                    <input type="text" id="company" value="{{ $data['company'] }}" disabled>        <br>

                    <label for="job">Puesto: </label><br>
                    <input type="text" id="job" value="{{ $data['job'] }}" disabled>
                @endif
            </div>
        </form>

        <div class="pnlInferiorEspecifico">
            <button type="submit" class="btn_create" onclick="abrirPnlEmergente()">Borrar datos</button> <br>
        </div>

</main>
@endsection

@section('panelesEmergentes')
    <div class='pnlEmergente' id="pnlEmergenteEspecificoDelete">
        <div class="pnlEmergenteTitulo">
            <i class="fa fa-exclamation-triangle" style="font-size:24px;color:white"></i>
            <p class="tituloEmergente"> Aviso </p>
        </div>
        <div class="pnlEmergentePrincipal">
            <p class="textoEmergente" id="textoEmergenteEspecifico">¿Está seguro de que quiere eliminar su cuenta?</p>
        </div>
        <div class="pnlEmergenteBotones">
            @csrf
            <button class="btnEmergenteAceptarDelete" id="btnEmergenteAceptarDelete" type="submit" onclick="aceptarEmergenteEspecifico()">
                Aceptar
            </button>
            <button class="btnEmergente" id="btnEmergenteCancelar" type="submit" onclick="cerrarEmergetneEspecifico()">
                Cancelar
            </button>
        </div>


    </div>
@endsection
