/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 12:21:22
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-21 17:03:22
 * @Description: Controlador de la vista done_tasks.blade. Que corresponde a las opciones de menú tareas hechas y por hacer.
 *               Formatea las fechas para mostrarlas en los titulos y realiza la funcionalidad de pulsar en el botón de una columna.
 */

$(document).ready(function(){
        let dataTable = new DataTable('data_table_name');
    });
//--------------------------------------------------------------------------------------------------
/**
 *
 * @param {Identificador de la tarea que hemos seleccionado                    } param_id_tarea
 * @param {Booleano que determina si se muestra el botón para subir tareas o no} param_posibilidad_hacer_entrega
 */
function StudentClickColumnToDoTask(param_id_tarea, param_posibilidad_hacer_entrega){
    document.getElementById('id_task').innerText = param_id_tarea;

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
                    document.getElementById('titEmergenteShowData').innerText = 'Tarea entregada';
                } else {
                    document.getElementById('PanelTituloE'    ).style.backgroundColor = 'red';
                    document.getElementById('titEmergenteShowData').innerText = 'Fuera de plazo';
                }
            } else {
                document.getElementById('lbl_input_upload').style.visibility = 'visible';
                document.getElementById('input_upload'    ).style.visibility = 'visible';
                document.getElementById('PanelTituloE'    ).style.backgroundColor = '#0099cc';
                document.getElementById('titEmergenteShowData').innerText = 'Añadir o modificar entrega';
            }

            MostrarPanelFormulario(false);
            document.getElementById('guardar_tarea_estudiante').style.visibility = 'hidden';
        } else {
            texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            MostrarMensajeError(texto, true);
        }
    });
}
//--------------------------------------------------------------------------------------------------
/**
 *
 * @param {Fecha que tenemos que formatear, viene con el formato de la base de datos} param_fecha
 * @returns Fecha con formato yyyy-mm-dd para que el imput date la procese
 */
function FormatearFecha(param_fecha) {
    var fechaOriginal = param_fecha;
    var fechaCompleta = new Date(fechaOriginal);

    var year = fechaCompleta.getFullYear();
    var mes  = ('0' + (fechaCompleta.getMonth() + 1)).slice(-2);
    var day  = ('0' + fechaCompleta.getDate()).slice(-2);

    var fechaFormateada = year + '-' + mes + '-' + day;

    return fechaFormateada
}
//--------------------------------------------------------------------------------------------------
