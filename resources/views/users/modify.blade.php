@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}"
@endsection
@section('title', 'Modificar datos')

@section ('main')
<main>
    <div class="pnlSuperiorEspecifico">
        <h1 class="titulo">Modificar datos</h1>
    </div>
    <form action="{{route('users.modify.store')}}" method="POST">
        @csrf
        <div class= "pnlInferiorEspecifico">
            <h2 class="SubtituloApartado">Datos generales de los usuarios</h2>                  <br>

            <label for="name">Nombre: </label>                                                          <br>
            <input type="text" id="name" name="name" value=" {{ $data['name'] }} ">             <br>

            <label for="surname">Apellidos: </label>                                                    <br>
            <input type="text" id="surname" name="surname" value=" {{ $data['surname'] }} ">    <br>

            <label for="email">Email: </label><br>
            <input type="text" id="email" name="email" value=" {{ $data['email'] }} ">          <br>

            <label for="user">Usuario: </label><br>
            <input type="text" id="user" name="user" value=" {{ $data['user'] }} ">             <br>

            <label for="tipousuario">Tipo de usuario: </label>                                                  <br>
            <select id="tipo_usuario" name="tipousuario" value=" {{ $data['tipousuario'] }} ">
                <option value="1">Estudiante</option>
                <option value="2">Mentor</option>
            </select>                                                                           <br>

            <label for="campoestudio">Campo de estudio: </label><br>
            <select id="campo_estudio" name="campoestudio" value=" {{ $data['campoestudio'] }} ">
                <option value="1">Rama tecnológica</option>
                <option value="2">Rama biosanitaria</option>
                <option value="3">Rama de arte</option>
                <option value="4">Rama jurista</option>
                <option value="5">Rama lingüistica</option>
            </select>                                                                                  <br>

            <label for="description">Descripción: </label><br>
            <input type="text" id="description" name="description" value=" {{ $data['description'] }} "><br>

            @if ($data['tipousuario'] == 1)
                <h2>Datos especificos de los estudiantes:</h2><br>

                <label for="career">Estudios que cursas: </label><br>
                <input type="text" id="career">        <br>

                <label for="first_year">Año de comienzo: </label><br>
                <input type="numeric" id="first_year"> <br>

                <label for="duration">Duración: </label><br>
                <input type="numeric" id="duration">   <br>
            @else
                <h2>Datos especificos de los mentores:<h2><br>

                <label for="company">Empresa: </label><br>
                <input type="text" id="company">        <br>

                <label for="job">Puesto: </label><br>
                <input type="text" id="job">
            @endif
        </div>
        <div class="pnlInferiorEspecifico">
            <button type="submit" class="btn_create">Modificar datos</button> <br>
        </div>
    </form>
    <!--
        @csrf
		<div class="container">
			<div class="left-field">
				<label for="name">Nombre:</label>
                @error('name')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="name" name="name" value=" {{ $data['name'] }} ">
			</div>
			<div class="right-field">
				<label for="surname">Apellidos:</label>
                @error('surname')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="surname" name="surname" value=" {{ $data['surname'] }} ">
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="email">Email:</label>
                @error('email')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="email" name="email" value=" {{ $data['email'] }} ">

			</div>
            <div class="right-field">
                <label for="user">Usuario:</label>
                @error('user')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <input type="text" id="user" name="user" value=" {{ $data['user'] }} ">

            </div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="tipo_usuario">Tipo de usuario:</label>
                @error('tipo_usuario')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<select id="tipo_usuario" name="tipousuario" value=" {{ $data['tipousuario'] }} ">
					<option value="1">Estudiante</option>
					<option value="2">Mentor</option>
				</select>

			</div>
			<div class="right-field">
				<label for="campo_estudio">Campo de estudio:</label>
                @error('campo_estudio')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <select id="campo_estudio" name="campoestudio" value=" {{ $data['campoestudio'] }} ">
                    <option value="1">Rama tecnológica</option>
                    <option value="2">Rama biosanitaria</option>
                    <option value="3">Rama de arte</option>
                    <option value="4">Rama jurista</option>
                    <option value="5">Rama lingüistica</option>
                </select>
			</div>
		</div>

        <div class="container">
            <div class="all">
                <label for="description">Descripción:</label>
                @error('record')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <input type="text" id="description" name="description" value=" {{ $data['description'] }} ">
            </div>
        </div>

        @foreach ($errors->all() as $error)
            <small>{{ $error }}</small>
            <br>
            <br>
         @endforeach


	</form>-->
</main>
@endsection
