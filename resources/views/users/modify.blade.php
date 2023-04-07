@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/estiloFormNoLog.css') }}" >
@endsection
@section('title', 'Modificar datos')

@section ('main')
<main>
    <form action="{{route('users.modify.store')}}" method="POST">
        <h1>Modificar datos</h1>
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

		<button type="submit">Modificar datos</button>
	</form>
</main>
@endsection
