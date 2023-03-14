<!DOCTYPE html>
<html>
<head>
	<title>Registro de usuarios</title>
	<link href="{{ asset('css/estiloFormNoLog.css') }}" rel="stylesheet">
</head>
<body>
	<form action="{{route('users.create.store')}}" method="POST">

        @csrf

        <h1>Registro de usuarios</h1>

		<div class="container">
			<div class="left-field">
				<label for="name">Nombre:</label>
                @error('name')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="name" name="name" value=" {{old('name')}} ">
			</div>
			<div class="right-field">
				<label for="surname">Apellidos:</label>
                @error('surname')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="surname" name="surname" value=" {{old('surname')}} ">
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="email">Email:</label>
                @error('email')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="email" name="email" value=" {{old('email')}} ">

			</div>
            <div class="right-field">
                <label for="user">Usuario:</label>
                @error('user')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <input type="text" id="user" name="user" value=" {{old('user')}} ">

            </div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="password">Contraseña:</label>
                @error('password')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="password" id="password" name="password">
			</div>
			<div class="right-field">
				<label for="rep_password">Repite contraseña:</label>
                @error('rep_password')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="password" id="rep_password" name="rep_password">
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="tipo_usuario">Tipo de usuario:</label>
                @error('tipo_usuario')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<select id="tipo_usuario" name="tipousuario" value=" {{old('tipousuario')}} ">
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
				<input type="text" id="campo_estudio" name="campo_estudio" value=" {{old('campo_estudio')}} ">
			</div>
		</div>

        <div class="container">
            <div class="all">
                <label for="description">Descripción:</label>
                @error('record')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
                <input type="text" id="description" name="description" value=" {{old('description')}} ">
            </div>
        </div>

		<button type="submit">Crear usuario</button>
	</form>
</body>
</html>
