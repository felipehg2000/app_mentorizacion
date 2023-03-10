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
<html lang = "en">
    <head>
        <meta charset="UFT-8">
        <meta name="viewport" content="width=device-width, initial-scale-1.0">

        <link href = "http://localhost/app_mentorizacion/resources/css/users/index.css" rel="stylesheet">
        <title>Inicio de sesión</title>
    </head>
    <body>
        <div class="container">
            <div class="login-form">

                <h1>Iniciar sesión</h1>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf <!-- se utiliza para añadir un token oculto al envío de datos, nos obliga laravel a ponerlo porque si no dará error el envío -->

                    <div class="form-group">
                        <label for="user">Usuario:</label>
                        <input type="text" id="user" name="user">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password">
                    </div>

                    <button type="submit" class="btn-submit">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </body>
</html>
