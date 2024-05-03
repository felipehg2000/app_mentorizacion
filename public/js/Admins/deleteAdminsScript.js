$(document).ready(function(){
    let dataTable = new DataTable('data_table_name');
});

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
