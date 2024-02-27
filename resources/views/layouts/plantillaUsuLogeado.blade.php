<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>

    <!--ESTILOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">

    <link href="{{ asset('css/plantilaUsuLogueadoStyle.css') }}" rel="stylesheet">

    <!--YAJRA STYLE-->
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
    @yield('style')

    <!--JAVA SCRIPTS-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/Layouts/PlantillausuLogueadoScript.js') }}"></script>

    <!--YAJRA STYLE-->
    <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script>
        var csrfToken                  = '{{ csrf_token() }}';
        var url_tablon_completo        = "{{route('users.task_board')}}";
        var url_tareas_completadas     = "{{route('users.done_tasks')}}";
        var url_tareas_a_completar     = "#";
        var url_chats_privados         = "{{route('users.sync_chat')}}";
        var url_informacion            = "#";
        var url_solicitudes            = "#";
        var url_acceso_a_tutoria       = "#";
        var url_amigos_actuales        = "{{route('users.actual_friends')}}";
        var url_solicitudes_de_amistad = "{{route('users.friendship')}}";
        var url_modificar_mis_datos    = "{{route('users.modify')}}";
        var url_eliminar_mi_cuenta     = "{{route('users.delete')}}";
        var url_cerrar_sesion          = "{{route('users.close')}}";
    </script>

    @yield('js')
</head>
<body>

    <!--HEADER-->
    <!--Parte de la navegación-->
    <nav>
        <img src = " {{ asset('photos/logo_blanco.JPG') }}" class="logo">
        <img src = " {{ asset('photos/my_image.png') }} " class="perfil_image">
    </nav>
    <!--Pantalla sin la parte de la navegación-->
    <div class="pnlMain">
        <!--Panel princiapl-->
        <div class="pnlPrincipal">
            <!--Apartado del menú-->
            <div class="pnlLeft">
                <div class="menu">Tablón de anuncios      </div>
                    <div class="submenu" id="submenu_1" onclick="redirection(1)">Tablón completo   </div>
                    <div class="submenu" id="submenu_2" onclick="redirection(2)">Tareas completadas</div>
                    <div class="submenu" id="submenu_3" onclick="redirection(3)">Tareas a completar</div>

                <div class="menu">Chats               </div>
                    <div class="submenu" id="submenu_4" onclick="redirection(4)">Cats privados</div>

                <div class="menu">Tutorías                  </div>
                    <div class="submenu" id="submenu_5" onclick="redirection(5)">Información        </div>
                    <div class="submenu" id="submenu_6" onclick="redirection(6)">Solicitudes        </div>
                    <div class="submenu" id="submenu_7" onclick="redirection(7)">Acceso a la tutoría</div>

                <div class="menu">Gestión de amistades         </div>
                    <div class="submenu" id="submenu_8" onclick="redirection(8)">Amigos actuales       </div>
                    <div class="submenu" id="submenu_9" onclick="redirection(9)">Solicitudes de amistad</div>

                <div class="menu">Gestión de usuario        </div>
                    <div class="submenu" id="submenu_10" onclick="redirection(10)">Modificar mis datos </div>
                    <div class="submenu" id="submenu_11" onclick="redirection(11)">Eliminar mi cuenta  </div>
                    <div class="submenu" id="submenu_12" onclick="redirection(12)">Cerrar sesión       </div>
            </div>
            <!--Apartado del resto de la pantalla-->
            <div class="pnlRight">
                @yield('main')
            </div>
        </div>
        <!--Panel para oscurecer la pantalla-->
        <div class="pnlOscurecer" id="pnlOscurecer">
        </div>
        <!--Ventana emergente-->
        <div class="pnlEmergente" id="pnlEmergente">
            <div class="pnlEmergenteTitulo">
                <i class="fa fa-exclamation-triangle" style="font-size:24px;color:white"></i>
                <p class="tituloEmergente"> Aviso </p>
            </div>
            <div class="pnlEmergentePrincipal">
                <p class="textoEmergente" id="textoEmergente"> Texto emergente</p>
                <br>
                <input class="edtPnlEmergente" id="edtPnlEmergente" type="password" placeholder="Introduzca su contraseña">
            </div>
            <div class="pnlEmergenteBotones">
                @csrf
                <button class="btnEmergente" id="btnEmergenteAceptar" type="submit" onclick="aceptarEmergente()">
                    Aceptar
                </button>
                <button class="btnEmergente" id="btnEmergenteCancelar" type="submit" onclick="cerrarEmergetne()">
                    Cancelar
                </button>
            </div>
        </div>

        <div class='pnlRespuestaEmergente' id='pnlRespuestaEmergente'>
            <div class='pnlEmergenteTitulo'>
                <i class="fa fa-exclamation-triangle" style="font-size:24px;color:white"></i>
                <p class="tituloEmergente"> Aviso </p>
            </div>
            <div class='pnlEmergentePrincipal'>
                <p class='textoEmergente' id='textoEmergenteRespuesta'>Texto respuesta emergente</p>
            </div>
            <div class='pnlEmergenteBotones'>
                <button class="btnEmergente" id="btnEmergenteAceptar" type="submit" onclick="aceptarPnlRespuestaEmergente()">
                    Aceptar
                </button>
            </div>
        </div>

        @yield('panelesEmergentes')
    </div>

    @stack('scripts_yajra')
</body>
</html>
