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
				<input type="text" id="name" name="name" required>
			</div>
			<div class="right-field">
				<label for="surname">Apellidos:</label>
				<input type="text" id="surname" name="surname">
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="email">Email:</label>
				<input type="text" id="email" name="email" required>
			</div>
            <div class="right-field">
                <label for="user">Usuario:</label>
                <input type="text" id="user" name="user" required>
            </div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="password">Contraseña:</label>
				<input type="password" id="password" name="password" required>
			</div>
			<div class="right-field">
				<label for="rep_password">Repite contraseña:</label>
				<input type="password" id="rep_password" name="rep_password" required>
			</div>
		</div>
		<div class="container">
			<div class="left-field">
				<label for="tipo-usuario">Tipo de usuario:</label>
				<select id="tipo-usuario" name="tipo-usuario" required>
					<option value="estudiante">Estudiante</option>
					<option value="mentor">Mentor</option>
				</select>
			</div>
			<div class="right-field">
				<label for="campo-estudio">Campo de estudio:</label>
				<input type="text" id="campo-estudio" name="campo-estudio" required>
			</div>
		</div>

        <div class="container">
            <div class="all">
                <label for="description">Descripción:</label>
                <input type="text" id="description" name="description">
            </div>
        </div>

		<button type="submit">Crear usuario</button>
	</form>
</body>
</html>
