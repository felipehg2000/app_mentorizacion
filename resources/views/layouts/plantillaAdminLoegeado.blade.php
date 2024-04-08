<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Administrador</title>

    <!--ESTILOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">

    <link href="{{ asset('css/plantilaUsuLogueadoStyle.css') }}" rel="stylesheet">

    <!--YAJRA STYLE-->
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
    @yield('style')

    <!--JAVA SCRIPTS-->
        <!--Biblioteca pusher-->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/Layouts/PlantillaAdminLogeado.js') }}"></script>

    <!--YAJRA STYLE-->
    <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script>
        var csrfToken                  = '{{ csrf_token()                  }}';

        var url_bloquear_mentores      = "{{ route('admin.block_mentores') }}";
        var url_bloquear_estudiantes   = "{{ route('admin.block_students') }}";
        var url_bloquear_admins        = "{{ route('admin.block_admins'  ) }}";
        var url_primeros_pasos         = "{{ route('admin.tutorial'      ) }}";
        var url_novedades              = "{{ route('admin.news'          ) }}";
        var url_create_admin           = "{{ route('admin.create'        ) }}";
        var url_modificar_mis_datos    = "{{ route('admin.modify'        ) }}";
        var url_eliminar_mi_cuenta     = "{{ route('admin.delete'        ) }}";
        var url_cerrar_sesion          = "{{ route('users.close'         ) }}";

        var url_datos_inicio           = "{{ route('users.info_inicial.store') }}";
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
                <div class="menu">Bloquear cuentas      </div>
                    <div class="submenu" id="submenu_1" onclick="redirection(1)">Bloquear mentores       </div>
                    <div class="submenu" id="submenu_2" onclick="redirection(2)">Bloquear estudiantes    </div>
                    <div class="submenu" id="submenu_9" onclick="redirection(9)">Bloquear administradores</div>

                <div class="menu">Guías de uso</div>
                    <div class="submenu" id="submenu_3" onclick="redirection(3)">Primeros pasos</div>
                    <div class="submenu" id="submenu_4" onclick="redirection(4)">Novedades     </div>

                <div class="menu">Gestión de usuario        </div>
                    <div class="submenu" id="submenu_8" onclick="redirection(8)">Crear administrador </div>
                    <div class="submenu" id="submenu_5" onclick="redirection(5)">Modificar mis datos </div>
                    <div class="submenu" id="submenu_6" onclick="redirection(6)">Eliminar cuentas    </div>
                    <div class="submenu" id="submenu_7" onclick="redirection(7)">Cerrar sesión       </div>
            </div>
            <!--Apartado del resto de la pantalla-->
            <div class="pnlRight">
                <div class='pnlCarga' id='pnlCarga'>
                    <i class="fa-li fa fa-spinner fa-spin"></i>
                </div>

                <div class='pnlCubierta' id='pnlCubierta'>
                    <div class='pnlCubiertaMensaje' id="pnlCubiertaMensaje"></div>
                </div>

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
