<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:20:21
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-21 17:24:19
 * @Description: Vista asociada a la opción de menú Primeros Pasos del apartado Guías de uso para el tipo de usuairo administrador.
 */
-->
@extends('layouts.plantillaAdminLoegeado')

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
                <p class='TaskTitulo'>Ver reportes:</p>
                <p>
                    La interfaz se basa en una tabla con los diferentes tipos de reportes que han hecho, asociados a la persona que ha sido reportada.
                    Simplemente es una ventana informativa para que los administradores puedan ver esa información.
                </p>
            </div><br>
            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Bannear cuentas:</p>
                <p>
                    La interfaz se conforma de una lista con todos los usuarios del tipo que se haya seleccionado. Para banearlos lo único que hay que hacer
                    es pulsar sobre el botón de baneo del usuario que elijamos. Una vez pulsado saldrá un mensaje informativo.
                </p>
            </div><br>
            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Modificar datos:</p>
                <p>
                    La interfaz se conforma de un formulario relleno con nuestros datos actuales. Al modificar cualquiera de los datos y darle a guardar,
                    en caso de que se cumplan los requisitos necesarios, los datos quedarán modificados y saldrá un mensaje informativo. Si hay algún error
                    saldrá un mensaje informativo y los datos no se modificarán.
                </p>
            </div><br>
            <div class='PanelFormNewTask'>
                <p class='TaskTitulo'>Crietrios de baneo:</p>
                <p>
                    Para que un administrador pueda banear a un usuario, el usuario debe haber sido reportado de una de las siguientes maneras. <br><br>

                    Si el reportado es un estudiante se baneará siempre sin tener en cuenta el tipo de reporte seleccionado ya que, damos por supuesto que los mentores
                    son personas serias que no quieren dañar a un alumno por el simple hecho de molestarle o hacerle un mal personal. A excepción de que se reporte por
                    nombre no apropiado y se pueda comprobar que es falso.<br><br>

                    Si el reportado es un mentor se actuará de la siguiente manera:<br>
                    1.	En caso de que el mentor tenga más de un reporte del mismo tipo hecho por diferentes estudiantes se baneará su cuenta.<br>
                    2.	En caso de que el reporte sea por abuso verbal se reportará su cuenta.<br>
                    3.	Si tiene más de 3 reportes diferentes por el mismo o distintos usuarios se baneará su cuenta.<br>
                    4.	En caso de que el reporte sea por nombre ofensivo y sea cierto, se baneará su cuenta.<br><br>

                    Estos serán los criterios principales que deberán seguir los administradores para banear cuentas. En caso de adquirir conocimiento
                    de que alguno de los administradores se salta estos criterios se procederá al baneo de la cuenta de dicho administrador.<br>

                </p>
            </div>
        </div>

    </div>
</div>

@endsection
