<!--
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:16:40
 * @Last Modified by:   undefined
 * @Last Modified time: 2023-03-06 23:16:40
 * @Description: Vista encargada de mostrar la pantalla de login, habrá un formulario con usuario y contraseña para hacer el login:
                    1.- Al pulsar el botón de inicio de sesión llamaremos a la fucnión users.store donde se comprobarán los datos introducidos para redireccionar.
                    2.- Al pulsar el botón de creación de cuenta se redirigirá a la vista de la ruta user.create.
 -->


 <!DOCTYPE html>
<html>
<head>
	<title>Inicio de sesión</title>
	<link href="{{ asset('css/estiloFormNoLog.css') }}" rel="stylesheet">
</head>
<body>
	<form action="{{ route('users.store') }}" method="POST">
		<h1>Inicio de sesión</h1>

        @csrf <!-- se utiliza para añadir un token oculto al envío de datos, nos obliga laravel a ponerlo porque si no dará error el envío -->

		<div class="container">
            <div class="all">
				<label for="usuario">Usuario:</label>
                @error('usuario')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="text" id="usuario" name="usuario" value=" {{old('usuario')}} ">
			</div>
        </div>

        <div class="container">
            <div class="all">
				<label for="password">Contraseña:</label>
                @error('password')
                    <br>
                    <small>*{{ $message }}</small>
                @enderror
				<input type="password" id="password" name="password">
			</div>
        </div>
        @foreach ($errors->all() as $error)
            <small>{{ $error }}</small>
            <br>
            <br>
         @endforeach

        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
