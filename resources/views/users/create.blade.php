<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UFT-8">
        <meta name="viewport" content="width=device-width, initial-scale-1.0">
        <link href = "http://localhost/app_mentorizacion/resources/css/users/index.css" rel="stylesheet">
        <title>Registro de usuarios</title>
    </head>
    <body>
        <div class="container">
            <div class="login-form">

                <h1>Crear usuario nuevo</h1>

                <form action="{{route('users.create.store')}}" method="POST">

                @csrf

                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name">
                </div>

                <div class="form-group">
                    <label for="surname">Apellidos:</label>
                    <input type="text" id="surname" name="surname">
                </div>

                <div class="form-group">
                    <label for="mail">Email:</label>
                    <input type="text" id="mail" name="mail">
                </div>

                <div class="form-group">
                    <label for="user">Usuario:</label>
                    <input type="text" id="user" name="user">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="repeat_password">Repetir contraseña:</label>
                    <input type="password" id="repeat_password" name="repeat_password">
                </div>

                <div class="form-group">
                    <label for="mentor">Mentor:</label>
                    <input type="text" id="mentor" name="mentor">
                </div>

                <div class="form-group">
                    <label for="study_area">Area de estudio:</label>
                    <input type="text" id="study_area" name="study_area">
                </div>

                <button type="submit" class="btn-submit">Crear cuenta</button>
        </form>
    </body>
</html>
