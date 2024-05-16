/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 13:25:14
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-16 13:25:59
 * @Description: Controlador de la vista delete del administrador.
 */

$(document).ready(function(){
    let dataTable = new DataTable('data_table_name');
});

/**
 * Llama a la función encargada de borrar los datos del administrador seleccionado y gestiona la respuesta del controladro
 *
 * @param {Identificador del usuario seleccionado} param_user_id
 */
function AdminClickTable(param_user_id){
    var data = {
        _token : csrfToken,
        id     : param_user_id
    }

    $.ajax({
        url   : url_delete_admin,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            MostrarMensajeError('Usuario borrado correctamente', true);
        } else {
            MostrarMensajeError('El usuario no se ha podido borrar. No se ha encontrado o quería eliminar su propia cuenta', true);
        }

        $('#data_table_name').DataTable().ajax.reload();
    });
}
