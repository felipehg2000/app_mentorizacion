/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 13:00:37
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-16 13:02:55
 * @Description: Controlador de las vista blick_users.blade crea el data table y la función del action botton.
 */

$(document).ready(function(){
    let dataTable = new DataTable('data_table_name');
});

/**
 * Llama a una función para bloquearlo y muestra un mensaje diciendo si es o no capaz de realizar el proceso
 *
 * @param {Identificador del usuario seleccionado} param_user_id
 */
function AdminClickTable(param_user_id){
    var data = {
        _token : csrfToken,
        id     : param_user_id
    }

    $.ajax({
        url   : url_bann_people,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            MostrarMensajeError('Usuario modificado correctamente', true);
        } else {
            MostrarMensajeError('El usuario no se ha modificado correctamente', true);
        }

        $('#data_table_name').DataTable().ajax.reload();
    });
}
