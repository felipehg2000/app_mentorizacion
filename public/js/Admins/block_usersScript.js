$(document).ready(function(){
    let dataTable = new DataTable('data_table_name');
});

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
