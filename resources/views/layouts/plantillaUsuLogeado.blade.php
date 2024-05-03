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
        <!--Biblioteca pusher-->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/Layouts/PlantillausuLogueadoScript.js') }}"></script>

    <!--YAJRA STYLE-->
    <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script>
        var csrfToken                  = '{{ csrf_token()                   }}';
        var url_tablon_completo        = "{{ route('users.task_board'     ) }}";
        var url_tareas_completadas     = "{{ route('users.done_tasks'     ) }}";
        var url_tareas_a_completar     = "{{ route('users.to_do_tasks'    ) }}";
        var url_chats_privados         = "{{ route('users.sync_chat'      ) }}";
        var url_solicitudes            = "{{ route('users.tut_request'    ) }}";
        var url_acceso_a_tutoria       = "{{ route('users.tut_access'     ) }}";
        var url_amigos_actuales        = "{{ route('users.actual_friends' ) }}";
        var url_solicitudes_de_amistad = "{{ route('users.friendship'     ) }}";
        var url_informacion            = "{{ route('users.tutorial'       ) }}";
        var url_novedades              = "{{ route('users.news'           ) }}";
        var url_modificar_mis_datos    = "{{ route('users.modify'         ) }}";
        var url_change_password        = "{{ route('users.modify_password') }}";
        var url_change_porfile_img     = "{{ route('users.modify_img_perf') }}";
        var url_eliminar_mi_cuenta     = "{{ route('users.delete'         ) }}";
        var url_cerrar_sesion          = "{{ route('users.close'          ) }}";

        var url_datos_inicio           = "{{ route('users.info_inicial.store'               ) }}";
        var url_friend_req_saw         = "{{ route('users.FriendRequestsSaw'                ) }}";
        var url_tut_saw                = "{{ route('users.TutoringSaw'                      ) }}";
        var url_tut_mod_not            = "{{ route('users.TutoringModificationsNotification') }}";
        var url_task_saw               = "{{ route('users.TasksSaw'                         ) }}";
        var url_answer_saw             = "{{ route('users.AnswersSaw'                       ) }}";

        var url_decrypt_info = "{{ route('users.decrypt_info.store') }}";
    </script>

    @yield('js')
</head>
<body>
    <!--HEADER-->
    <!--Parte de la navegación-->
    <nav>
        <img src = " {{ asset('photos/logo_blanco.JPG') }}" class="logo">
        <!--Ponemos el time() para que no cargue la que tiene en caché-->
        <img src = " {{ asset('photos/my_image.JPG') }} ? {{ time() }}" class="perfil_image">
    </nav>
    <!--Pantalla sin la parte de la navegación-->
    <div class="pnlMain">
        <!--Panel princiapl-->
        <div class="pnlPrincipal">
            <!--Apartado del menú-->
            <div class="pnlLeft">
                <div class="menu">Tablón de anuncios      </div>
                    <div class="submenu" id="submenu_1" onclick="redirection(1)">Tablón completo   <div class='notification' id='notification_1'></div></div>
                    <div class="submenu" id="submenu_2" onclick="redirection(2)">Tareas completadas<div class='notification' id='notification_2'></div></div>
                    <div class="submenu" id="submenu_3" onclick="redirection(3)">Tareas a completar<div class='notification' id='notification_3'></div></div>

                <div class="menu">Chats               </div>
                    <div class="submenu" id="submenu_4" onclick="redirection(4)">Chats privados<div class='notification' id='notification_4'></div></div>

                <div class="menu">Tutorías                  </div>
                    <div class="submenu" id="submenu_6" onclick="redirection(6)">Solicitudes        <div class='notification' id='notification_6'></div></div>
                    <div class="submenu" id="submenu_7" onclick="redirection(7)">Acceso a la tutoría<div class='notification' id='notification_7'></div></div>

                <div class="menu">Sala de estudios         </div>
                    <div class="submenu" id="submenu_8" onclick="redirection(8)">Integrantes    <div class='notification' id='notification_8'></div></div>
                    <div class="submenu" id="submenu_9" onclick="redirection(9)">Solicitudes    <div class='notification' id='notification_9'></div></div>

                <div class="menu">Guías de uso</div>
                    <div class="submenu" id="submenu_5"  onclick="redirection(5)">Primeros pasos   <div class='notification' id='notification_5'></div></div>
                    <div class="submenu" id="submenu_13" onclick="redirection(13)">Novedades       <div class='notification' id='notification_13'></div></div>

                <div class="menu">Gestión de usuario        </div>
                    <div class="submenu" id="submenu_10" onclick="redirection(10)">Modificar mis datos <div class='notification' id='notification_10'></div></div>
                    <div class="submenu" id="submenu_14" onclick="redirection(14)">Modificar contraseña<div class='notification' id='notification_14'></div></div>
                    <div class="submenu" id="submenu_15" onclick="redirection(15)">Modificar imagen    <div class='notification' id='notification_15'></div></div>
                    <div class="submenu" id="submenu_11" onclick="redirection(11)">Eliminar mi cuenta  <div class='notification' id='notification_11'></div></div>
                    <div class="submenu" id="submenu_12" onclick="redirection(12)">Cerrar sesión       <div class='notification' id='notification_12'></div></div>
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

        <div class='pnlMensajeNuevo' id='pnlRespuestaEmergente'>
            <div class='pnlPrincipalMensajeNuevo' id='textoEmergenteRespuesta'>
                Nuevo mensaje del chat
            </div>

            <div class='pnlMensajeNuevoSuperior'>
                <div class='btnCerrar' onclick="MostrarMensajeError('', false)">x</div>
            </div>
        </div>


        <div class='pnlMensajeNuevo' id='pnlNotificacionMensajeNuevo'>
            <div class='pnlPrincipalMensajeNuevo'>
                Nuevo mensaje del chat
            </div>

            <div class='pnlMensajeNuevoSuperior'>
                <div class='btnCerrar' onclick="VisibilidadNotificacionNuevoMensaje(false)">x</div>
            </div>
        </div>

        @yield('panelesEmergentes')
    </div>

    @stack('scripts_yajra')
</body>
</html>
