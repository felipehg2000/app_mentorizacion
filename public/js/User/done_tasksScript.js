$(document).ready(function(){
        let dataTable = new DataTable('data_table_name');
    });


function StudentClickColumnToDoTask(param_id_tarea, param_posibilidad_hacer_entrega){
    var data = {
        _token : csrfToken,
        id     : param_id_tarea
    }

    $.ajax({
        url   : url_found_task,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            document.getElementById('input_name'       ).value = respuesta.tarea.TASK_TITLE;
            document.getElementById('input_description').value = respuesta.tarea.DESCRIPTION;
            document.getElementById('input_last_day'   ).value = FormatearFecha(respuesta.tarea.LAST_DAY);

            if (!FechaEsValida(respuesta.tarea.LAST_DAY) || !param_posibilidad_hacer_entrega){
                document.getElementById('lbl_input_upload').style.visibility = 'hidden';
                document.getElementById('input_upload'    ).style.visibility = 'hidden';
                if (!param_posibilidad_hacer_entrega){
                    document.getElementById('PanelTituloE'    ).style.backgroundColor = 'green';
                } else {
                    document.getElementById('PanelTituloE'    ).style.backgroundColor = 'red';
                }
            } else {
                document.getElementById('lbl_input_upload').style.visibility = 'visible';
                document.getElementById('input_upload'    ).style.visibility = 'visible';
                document.getElementById('PanelTituloE'    ).style.backgroundColor = '#0099cc';
            }

            MostrarPanelFormulario(false);
        } else {
            texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            MostrarMensajeError(texto);
        }
    });
}

function MentorClickColumnDataTableTask(param_task_id) {
    var data = {
        _token : csrfToken,
        id     : param_task_id
    }

    $.ajax({
        url   : url_found_answers,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            document.getElementById('pnlOscurecer').style.visibility = 'visible';

            document.getElementById('ListaEstudiantesConEntrega').innerHTML = '';
            document.getElementById('ListaEstudiantesSinEntrega').innerHTML = '';

            respuesta.usuarios_sala_estudio.forEach(function(usuario){
                var entregado = false;

                respuesta.usuarios_con_entrega.forEach(function(entrega){
                    if (entrega.id == usuario.id) {
                        entregado = true;
                        PushUser(entrega.id, entrega.NAME, entrega.SURNAME, 'ListaEstudiantesConEntrega');
                    }
                });

                if (!entregado){
                    PushUser(usuario.id, usuario.NAME, usuario.SURNAME, 'ListaEstudiantesSinEntrega');
                }
            });

            document.getElementById('PanelShowAnswers').style.visibility = 'visible';
        } else {
            //devolver la vista de cerrar sesión
            window.location.href = url_close;
        }
    });
}

//Borrar¿?
function clickColumnToDoTasks(param_user_type, param_task){
    if (param_user_type == 1) { //Estudiante
        alert('Patata estudiante');
    } else if (param_user_type == 2) { //Mentor
        alert('Patata mentor ' + param_task);
    }
}

function MostrarPanelFormulario(){
    document.getElementById('pnlOscurecer' ).style.visibility = 'visible';
    document.getElementById('PanelShowData').style.visibility = 'visible';
}

/**
 * Muestra el tablón de tareas
 */
function MostrarPanelTareas(param_campos_editables){
    document.getElementById('pnlOscurecer'    ).style.visibility = 'hidden';
    document.getElementById('PanelShowData'   ).style.visibility = 'hidden';
    document.getElementById('lbl_input_upload').style.visibility = 'hidden';
    document.getElementById('input_upload'    ).style.visibility = 'hidden';

    document.getElementById('PanelShowAnswers').style.visibility = 'hidden';

    if (!param_campos_editables){
        document.getElementById('input_name'       ).readOnly = true;
        document.getElementById('input_last_day'   ).readOnly = true;
        document.getElementById('input_description').readOnly = true;
    }
}

/**
 * Muestra el panel del mensaje de error con el texto que se le pasa por parametro
 *
 * @param {Texto que saldrá en el mensaje de error} param_texto
 */
function MostrarMensajeError(param_texto){

    document.getElementById('textoEmergenteRespuesta').textContent = param_texto;

    document.getElementById('pnlOscurecer'           ).style.visibility = 'visible';
    document.getElementById('pnlRespuestaEmergente'  ).style.visibility = 'visible';
}

function FormatearFecha(param_fecha) {
    var fechaOriginal = param_fecha;
    var fechaCompleta = new Date(fechaOriginal);

    var year = fechaCompleta.getFullYear();
    var mes  = ('0' + (fechaCompleta.getMonth() + 1)).slice(-2);
    var day  = ('0' + fechaCompleta.getDate()).slice(-2);

    var fechaFormateada = year + '-' + mes + '-' + day;

    return fechaFormateada
}

function FechaEsValida(param_fecha) {
    var fechaCompleta = new Date(param_fecha);
    var fechaHoy      = new Date();

    if (fechaCompleta >= fechaHoy){
        return true;
    } else {
        return false;
    }
}

function PushUser(pragma_usuario_id, pragma_usuario_nombre, pragma_usuario_ape, pragma_nombre_lista) {
    var lista = document.getElementById(pragma_nombre_lista);

    var listItem = document.createElement('li');
    var anchor   = document.createElement('a' );

    var texto = pragma_usuario_nombre + " " + pragma_usuario_ape;

    anchor.href        = '';
    anchor.textContent = texto;

    /**
     * Si quiero hacer que salte una función js en vez de una ruta url puedo hacer lo siguiente:
     *    anchor.href    = 'javascript:void(0)';
     *    anchor.onclick = nombreFuncion;
     */

    listItem.appendChild(anchor  );
    lista   .appendChild(listItem);
}
