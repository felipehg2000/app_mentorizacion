<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>

    <!--ESTILOS-->
    <link href="{{ asset('css/plantilaUsuLogueadoStyle.css') }}" rel="stylesheet">
    @yield('style')

    <!--JAVA SCRIPTS-->
    @yield('js')

</head>
<body>

    <!--HEADER-->
    <!--NAV-->
    <nav>
        <img src=" {{ asset('photos/logo_blanco.JPG') }}" class="logo">
        <img src = " {{ asset('photos/my_image.png') }} " class="perfil_image">
    </nav>

    <div class="pnlPrincipal">
        <div class="pnlLeft">
            <div class="menu">Tablón de anuncios      </div>
                <div class="submenu" onclick="redirection(1)">Tablón completo   </div>
                <div class="submenu" onclick="redirection(2)">Tareas completadas</div>
                <div class="submenu" onclick="redirection(3)">Tareas a completar</div>

            <div class="menu">Chats               </div>
                <div class="submenu" onclick="redirection(4)">Cats privados</div>

            <div class="menu">Tutorías                  </div>
                <div class="submenu" onclick="redirection(5)">Información        </div>
                <div class="submenu" onclick="redirection(6)">Solicitudes        </div>
                <div class="submenu" onclick="redirection(7)">Acceso a la tutoría</div>

            <div class="menu">Gestión de amistades         </div>
                <div class="submenu" onclick="redirection(8)">Amigos actuales       </div>
                <div class="submenu" onclick="redirection(9)">Solicitudes de amistad</div>

            <div class="menu">Gestión de usuario        </div>
                <div class="submenu" onclick="redirection(10)">Modificar mis datos </div>
                <div class="submenu" onclick="redirection(11)">Eliminar mi cuenta  </div>
                <div class="submenu" onclick="redirection(12)">Cerrar sesión       </div>
        </div>

        <div class="pnlRight">
            @yield('main')
        </div>
    </div>
    @yield('script')
</body>
</html>
