<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>

    <!--ESTILOS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/plantilaUsuLogueadoStyle.css') }}" rel="stylesheet">
    @yield('style')

    <!--JAVA SCRIPTS-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var csrfToken = '{{ csrf_token() }}';
    </script>


    @yield('js')
    <script>
        function redirection(index) {
            switch(index){
                case 1:
                    window.location.href = "#";
                    break;
                case 2:
                    window.location.href = "#";
                    break;
                case 3:
                    window.location.href = "#";
                    break;
                case 4:
                    window.location.href = "{{route('users.sync_chat')}}";
                    break;
                case 5:
                    window.location.href = "#";
                    break;
                case 6:
                    window.location.href = "#";
                    break;
                case 7:
                    window.location.href = "#";
                    break;
                case 8:
                    window.location.href = "{{route('users.actual_friends')}}";
                    break;
                case 9:
                    window.location.href = "{{route('users.friendship')}}";
                    break;
                case 10:
                    window.location.href = "{{route('users.modify')}}";
                    break;
                case 11:
                    window.location.href = "{{route('users.delete')}}";
                    break;
                case 12:
                    window.location.href = "{{route('users.close')}}";
                    break;
            }
        }

        function cerrarEmergetne(){
            document.getElementById("pnlOscurecer"   ).style.visibility = "hidden";
            document.getElementById("pnlEmergente"   ).style.visibility = "hidden";
            document.getElementById("edtPnlEmergente").style.visibility = "hidden";
        }

        function aceptarPnlRespuestaEmergente(){
            document.getElementById("pnlOscurecer"            ).style.visibility = "hidden";
            document.getElementById("pnlRespuestaEmergente"   ).style.visibility = "hidden";
        }
    </script>

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
</body>
</html>
