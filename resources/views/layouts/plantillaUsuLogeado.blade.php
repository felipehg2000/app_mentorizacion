<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>

    <!--ESTILOS-->
    <style type="text/css">
        body {
            margin : 0;
            padding: 0;
            background-color: #gray;
        }
        /*Estilo genérico para la barra de navegación*/
        nav {
            padding         : 10px;
            background-color: #0099cc;
            color           : #fff;
            display         : flex;
            align-items     : center;
            justify-content : space-between;
            box-sizing      : border-box;
        }

        /*Estilo genérico para las listas dentro de la barra de navegación*/
        nav ul {
            list-style  : none;
            display     : flex;
            align-items : center;
            margin      : 0;
            padding     : 0;
        }

        nav li {
            margin: 0 10px;
        }

        /*Estilo para el logo dentro de la barra de navegación*/
        .logo {
            height      : 30px;
            width       : auto;
            margin-left : 5%;
            border-style: solid;
            border-color: black;
        }

       /*Estilo para un menú desplegable dentro de la barra de navegación*/
        .menuDesplegable {
            position: relative;
        }

        .menuDesplegable ul {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            z-index: 1;
            background-color: white;
            border-style: solid;
            border-color: black;
        }

        .menuDesplegable:hover > ul {
            display: flex;
            flex-direction: column;
        }

        .menuDesplegable ul li {
            margin-bottom: 5px;
            width:100%;
        }

        /*Estilo del botón que despliega el menú de opciones*/
        /*.perfil {
            border-style: solid;
            border-radius: 100%;
            border-color: black;
            background-color: white;
            color: black;
            padding: 10px;
            text-decoration: none;
            font-weight: bold;
        }*/

        img.perfil_image{
            height: 40px;
            width : auto;
            border-style: solid;
            border-radius: 100%;
            border-color: black;
        }

        /*Estilo de las opciones del menú de desplegable de opciones*/
        .botonOpcion {

            background-color: white;
            color: black;
            text-decoration: none;
            font-weight: bold;
            align-content: center;

            display: block;
            width: 100%;
        }

        .botonOpcion:hover {
            background-color: aqua;
        }

        /*Espacio entre la barra de navegación y el pie de página y entre los bordes de la pantalla*/
        main {
            max-width: 85%;
            margin: 0 auto;
            padding: 20px;
            margin-bottom: 50px;
            box-sizing: border-box;
        }
    </style>

    @yield('style')

    <!--JAVA SCRIPTS-->
    @yield('js')

</head>
<body>

    <!--HEADER-->
    <!--NAV-->
    <nav>
        <img src=" {{ asset('photos/logo_blanco.JPG') }}" class="logo">
        <ul>
            <li class="menuDesplegable">
                <a class="perfil"><img src = " {{ asset('photos/my_image.png') }} " class="perfil_image"></a>
                    <ul>
                        <li class="opcion"><a href=" {{ route('users.modify') }} " class="botonOpcion">Modificar Usuario</a></li>
                        <li class="opcion"><a href=" {{ route('users.delete') }} " class="botonOpcion">Borrar Usuario</a></li>
                        <li class="opcion"><a href=" {{ route('users.close') }}  " class="botonOpcion">Cerrar Sesión</a></li>
                    </ul>

            </li>

        </ul>
    </nav>
    <!--main-->
        @yield('main')
</body>
</html>
