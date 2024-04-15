function EliminarSeguidor(param_user_id){
    var data = {
        _token  : csrfToken,
        id_user : param_user_id
    }

    $.ajax({
        url   : url_act_friends_store,
        method: 'POST'       ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            location.reload();
        }
    });
}

function AbrirPanelReportes(param_user_id){
    document.getElementById('pnlOscurecer' ).style.visibility = 'visible';
    document.getElementById('PanelShowData').style.visibility = 'visible';

    document.getElementById('id_task').textContent = param_user_id;

    document.getElementById('PanelShowData').style.width = '60%';
}

function CerrarPanelReport(){
    document.getElementById('pnlOscurecer' ).style.visibility = 'hidden';
    document.getElementById('PanelShowData').style.visibility = 'hidden';
}

function CrearNuevoReport(){
    id_usu = document.getElementById('id_task').textContent;
    reason = document.querySelector('input[name="reason"]:checked').value;

    var data = {
        _token     : csrfToken,
        id_reported: id_usu   ,
        reason     : reason
    }

    $.ajax({
        url   : url_create_report_req,
        method: 'POST'       ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            CerrarPanelReport();

            alert('Reportado correctamente');
        }
    });
}
