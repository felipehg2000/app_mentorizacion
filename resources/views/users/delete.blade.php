@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/estiloFormNoLog.css') }}" >
@endsection
@section('title', 'Borrar datos')

@section ('main')
<main>
    <form action="{{route('users.delete.store')}}" method="POST">
        <h1>Borrar cuenta</h1>
        @csrf
		<div class="container">
			<div class="left-field">
				<label for="name">Nombre:</label>
                @error('name')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="name" name="name" value=" {{ $data['name'] }} " disabled>
			</div>
			<div class="right-field">
				<label for="surname">Apellidos:</label>
                @error('surname')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="surname" name="surname" value=" {{ $data['surname'] }} " disabled>
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="email">Email:</label>
                @error('email')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="email" name="email" value=" {{ $data['email'] }} " disabled>

			</div>
            <div class="right-field">
                <label for="user">Usuario:</label>
                @error('user')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <input type="text" id="user" name="user" value=" {{ $data['user'] }} " disabled>

            </div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="tipo_usuario">Tipo de usuario:</label>
                @error('tipo_usuario')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<select id="tipo_usuario" name="tipousuario" value=" {{ $data['tipousuario'] }} " disabled>
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
                <select id="campo_estudio" name="campoestudio" value=" {{ $data['campoestudio'] }} " disabled>
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
                <input type="text" id="description" name="description" value=" {{ $data['description'] }} " disabled>
            </div>
        </div>

        @foreach ($errors->all() as $error)
            <small>{{ $error }}</small>
            <br>
            <br>
         @endforeach

		<button type="submit">Borrar usuario</button>
	</form>
</main>
@endsection
