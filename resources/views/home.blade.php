<!DOCTYPE html>
<html>
<head>
	<title>Mentoring</title>
	<link href="{{ asset('css/estiloHome.css') }}" rel="stylesheet">
</head>
<body>
    <nav>
        <img src=" {{ asset('photos/logo_blanco.JPG') }}" class="logo">
        <ul>
            <li><a href=" {{ route('users.create') }}">Crear cuenta</a></li>
            <li><a href=" {{ route('users.index') }} ">Iniciar sesión</a></li>
        </ul>
    </nav>
    <main>
        <div class="contenedor">
            <div class="main-left">
                <h1>¿Que es?</h1>
                <p>
                    Mentoring es una red social donde podrás ponerte en contacto con profesionales o estudiantes de tu campo de conocimiento según tu tipo de
                    usuario. Los tipos de usuarios que hay son los siguientes:
                </p>
            </div>
            <img class="main-right" src=" {{ asset('photos/imagen_home_1.JPG') }} ">
        </div>

        <div class="contenedor">
            <img class="main-left" src=" {{ asset('photos/imagen_home_2.jpg') }} ">
            <div class="main-right">
                <h2>Mentores</h2>
                <p>
                    Los mentores al crear su cuenta e iniciar sesión en esta accederán a una sala sobre la que tendrán el control, en esta sala podrán realizar
                    acciones como subir tareas, contestar dudas de los alumnos o incluso dar tutorías para que estos entiendan mejor el mundo laboral.
                </p>
            </div>
        </div>

        <div class="contenedor">
            <div class="main-left">
                <h2>Estudiantes</h2>
                <p>
                    Los estudiantes al iniciar sesión podrán solicitar unirse a las salas de distintos mentores hasta que estén en una, cuando estén en una sala
                    podrán comunicarse con sus mentores y tener acceso a la información que estos tengan publicada.
                </p>
            </div>
            <img class="main-right" src=" {{ asset('photos/imagen_home_3.jpg') }} ">
        </div>
    </main>
    <footer>
        <p class="pie_de_pagina">
            Trabajo de fin de grado Felipe Hernández González: Red social de mentorización de alumnos
        </p>
    </footer>
</body>
<body>
