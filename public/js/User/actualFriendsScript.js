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
    reason = '';

    if (document.querySelector('input[name="reason"]:checked') == null){
        MostrarMensajeError('Para crear una solicitud de report debe seleccionar una de las opciones mostradas.');
        return;
    }

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
            MostrarMensajeError('Reportado correctamente');
            CerrarPanelReport();
        }
    });
}

function MostrarMensajeError(param_texto){

    document.getElementById('textoEmergenteRespuesta').textContent = param_texto;

    document.getElementById('pnlOscurecer'           ).style.visibility = 'visible';
    document.getElementById('pnlRespuestaEmergente'  ).style.visibility = 'visible';
}
