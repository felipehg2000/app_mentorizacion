@extends('layouts.plantillaUsuLogeado')

@section('title', 'Primeros pasos')

@section ('style')
    <!--Linkeamos los estilos-->
    <link href="{{ asset('css/task_boardStyle.css' ) }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimplestyle.css') }}" rel="stylesheet">
@endsection


@section ('main')

<div class='PanelTaskBoardPrincipal'>

    <div class='PanelTaskBoardTareas' id='PanelTaskBoardTareas'>
        <!--Parte superior de la web titulo + btn nueva tarea mentores-->
        <div class='PanelTaskBoardSuperiorMentor' id='PanelTaskBoardSuperiorMentor'>
            <h2>Tutoriales y Primeros Pasos:</h2>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Primer paso:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>General:</p>
                <p>
                    En esta aplicación hay dos roles principales, puedes ser mentor o estudiante y según el rol que desempeñes dentro de la aplicación
                    podrás acceder a unas funcionalidades u otras. Hay algunas funciones que son comunes para ambos roles.
                </p>
                <p class='TaskTitulo'>Mentores:</p>
                <p>
                    Los mentores desde el primer inicio de sesión pueden crear tareas pero tendrán el resto de opciones inaccesibles hasta que tengan
                    integrantes en su sala. Una vez reciba solicitudes de amistad de alumnos que quieren acceder a su sala podrán aceptarlas o rechazaras,
                    podrán aceptar hasta un máximo de 5 alumnos. Una vez tengan un alumno en su sala de estudios podrán acceder a la mayoría de opciones de esta.
                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>
                    Al iniciar sesión por primera vez solo podrán acceder a la búsqueda de mentores para solicitarles acceder a su sala de estudios. El resto de
                    las opciones serán inaccesibles hasta que estén dentro de una sala de estudios.
                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Tareas:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Mentores:</p>
                <p>
                    En este apartado, el mentor, podrá crear tareas y descargar las respuestas que le den sus alumnos. No habrá limite de creación de tareas.
                    Para hacer más facil el acceso a las respuestas de los alumnos, las tareas se podrán ver en dos tablas distintas, a parte de verlas en el
                    tablón de anuncios. Una de las tablas mostrará todas las tareas cuya fecha de finalización es posterior a la actual y la otra mostrará las
                    tareas cuya fecha es anterior o igual a la actual. Desde ambas tablas se podrán descargar las respuestas de los alumnos, también se podrá
                    hacer desde el tablón.
                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>
                    El estudiante podrá subir las respuestas a la tarea únicamente en documentos pdf. Al igual que el mentor, para que sea más facil saber las
                    tareas que ha realizado y las que no, estas, se organizarán en dos tablas a parte del tablón de anuncios. Una de las tablas mostrará las tareas
                    que el estudiante ha entregado, ya haya pasado la fecha de finalización de la tarea o no, y la otra tabla mostrará las actividades que no
                    tengan una respuesta asociada. Desde ambas tablas se podrá realizar o modificar la subida de respuestas, también se podrá hacer desde el tablón.
                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Chats:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Mentores:</p>
                <p>
                    Podrán acceder al chat de todos los estudiantes que tengan dentro de su sala de estudios. Únicamente se podrán comunicar a través de texto plano.
                </p>
                <p class='TaskTitulo'>Estudiantes:</p>
                <p>
                    Únicamente podrá acceder al chat de su mentor y solo se podrá comunicar con este utilizando texto plano.
                </p>
            </div>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Tutorías:</p>
            </div>

            <p class='TaskTitulo'>Mentores: </p>
            <p>
                Tendrán que esperar a que un estudiante solicite una tutoría para poder aceptarla o rechazarla.
            </p>

            <p class='TaskTitulo'>Estudiantes: </p>
            <p>
                Tendrá que solicitar al mentor una tutoría en una fecha y hora concretas.
            </p>

            <p class='TaskTitulo'>Común: </p>
            <p>
                Una vez la tutoría esté concertada, ambos usuarios podrán acceder a la tutoría el día en el que se haya concertado un poco antes de la hora
                seleccionada. Cada usuario tendrá un area de texto donde escribir y el otro usuario verá el texto en tiempo real para poder corregirlo o
                modificarlo. Un usuario no podrá modificar el texto del otro de forma directa, tendrá que copiarlo en su espacio de trabajo y modificarlo en
                este lugar.
            </p>
        </div>

        <div class='PanelNewTask'>
            <div class='TaskTitulo_Azul'>
                <p class='tituloEmergente'>Gestión de usuario:</p>
            </div>

            <div class='PanelFormNewTask'>
                <p>
                    Esta funcionalidad es igual para los mentores y los estudiantes. Ambos tendrán la posibilidad de visualizar o modificar sus datos. También podrán
                    eliminar sus cuentas, previamente tendrán que confirmar su identidad con el uso de la contraseña.
                </p>
            </div>
        </div>


    </div>
</div>
@endsection
