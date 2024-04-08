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
            alert('Usuario borrado correctamente');
        } else {
            alert('El usuario no se ha podido borrar. No se ha encontrado o quería eliminar su propia cuenta');
        }

        $('#data_table_name').DataTable().ajax.reload();
    });
}
