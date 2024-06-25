/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 13:10:12
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-17 13:13:37
 * @Description: Controlador de las vistas actualfriends tanto de mentores como de estudiantes.
 */

/**
 * Llamamos a la función que se encarga de hacer la baja en el controlador.
 *
 * @param {Identificador del usuario que queramos eliminar de seguidores.} param_user_id
 */
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

/**
 * Mostramos el panel emergente con una lista de quejas.
 *
 * @param {Identificador del usuario del que queramos poner una queja} param_user_id
 */
function AbrirPanelReportes(param_user_id){
    document.getElementById('pnlOscurecer' ).style.visibility = 'visible';
    document.getElementById('PanelShowData').style.visibility = 'visible';

    document.getElementById('id_task').textContent = param_user_id;

    document.getElementById('PanelShowData').style.width = '60%';
}

/**
 * Cerramos el panel de quejas
 */
function CerrarPanelReport(){
    document.getElementById('pnlOscurecer' ).style.visibility = 'hidden';
    document.getElementById('PanelShowData').style.visibility = 'hidden';
}

/**
 * Cogemos los datos necesarios y llamamos a la función encargada de crear la entrada en la base de datos.
 *
 * @returns Devuelve fin de función en caso de que no haya seleccionada ninguna queja.
 */
function CrearNuevoReport(){
    id_usu = document.getElementById('id_task').textContent;
    reason = '';

    if (document.querySelector('input[name="reason"]:checked') == null){
        MostrarMensajeError('Para crear una solicitud de report debe seleccionar una de las opciones mostradas.', true);
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
            MostrarMensajeError('Reportado correctamente', true);
            CerrarPanelReport();
        }
    });
}
