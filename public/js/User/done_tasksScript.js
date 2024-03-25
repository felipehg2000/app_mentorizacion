$(document).ready(function(){
        let dataTable = new DataTable('data_table_name');
    });
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
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
