<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UFT-8">
        <meta name="viewport" content="width=device-width, initial-scale-1.0">
        <title>Home</title>
    </head>
    <body>
        <h1>User create</h1>
            <form action="{{route('users.create.store')}}" method="POST">
                
                @csrf

                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name"><br><br>
                <label for="surname">Apellidos</label>
                <input type="text" id="surname" name="surname"><br><br>
                <label for="mail">Email</label>
                <input type="text" id="mail" name="mail"><br><br>
                <label for="user">Usuario</label>
                <input type="text" id="user" name="user"><br><br>
                <label for="password">Contraseña</label>
                <input type="text" id="password" name="password"><br><br>
                <label for="mentor">Mentor</label>
                <input type="text" id="mentor" name="mentor"><br><br>
                <label for="study_area">Area de estudio</label>
                <input type="text" id="study_area" name="study_area"><br><br>

                <button type="submit">Crear cuenta</button>
        </form>
    </body>
</html>