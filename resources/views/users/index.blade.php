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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="{{ asset('css/loginUserStyle.css') }}" rel="stylesheet">
    <script src="{{ asset('js/User/loginUserScript.js') }}"></script>
</head>
<body>
    <div class ="pnlPrincipal">
        <div class = "pnlSuperior">
            <h3>Inicio de sesión</h3>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="pnlClient">
                <div class = "pnlRight">
                    <label for="user" id="lbl_user" class="center"></label><br>
                    <input type="text" id="user" name="user" class="center" placeholder="Usuario" onfocus="createLabel(1)" onblur="deleteLabel(1)"><br>
                    <label for="password" id="lbl_password" class="center"></label><br>
                    <input type="password" id="password" name="password" class="center" placeholder="Contraseña" onfocus="createLabel(2)" onblur="deleteLabel(2)">
                    <a onmousedown='MouseDownPassword()' onmouseup='MouseUpPassword()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br><br>
                </div>
            </div>

            <div class="pnlInferior">
                @foreach ($errors->all() as $error)
                    <small class="error">{{ $error }}</small>
                @endforeach
                <button type="submit" class="btn_create">Iniciar sesión</button><br>
            <div>
        </form>
    </div>
</body>
</html>
