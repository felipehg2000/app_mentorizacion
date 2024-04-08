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
            alert('Usuario modificado correctamente');
        } else {
            alert('El usuario no se ha modificado correctamente');
        }

        $('#data_table_name').DataTable().ajax.reload();
    });
}
