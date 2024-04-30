function ModifyPassword(){
    var actual_p    = document.getElementById('actual_password').value;
    var nueva_p     = document.getElementById('password'       ).value;
    var nueva_p_rep = document.getElementById('rep_password'   ).value;

    if (actual_p == '' || nueva_p == '' || nueva_p_rep == ''){
        MostrarMensajeError('Error: no puede dejar campos en blanco');
        return;
    }

    if (nueva_p != nueva_p_rep){
        MostrarMensajeError('ERROR: los campos de contraseñas deben tener el mismo texto');
        document.getElementById('password'       ).textContent = '';
        document.getElementById('rep_password'   ).textContent = '';

        return;
    }

    var data = {
        _token      : csrfToken,
        actual_pass : actual_p ,
        nueva_pass  : nueva_p
    }

    $.ajax({
        url   : url_modify_store,
        method: 'POST'          ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            MostrarMensajeError('Datos modificados correctamente');
            document.getElementById('actual_password').textContent = '';
            document.getElementById('password'       ).textContent = '';
            document.getElementById('rep_password'   ).textContent = '';

        } else {
            MostrarMensajeError('ERROR: la contraseña actual no es correcta');
            document.getElementById('actual_password').textContent = '';

            return;
        }
    });
}
//--------------------------------------------------------------------------------------------------
/**
 * Muestra el panel del mensaje de error con el texto que se le pasa por parametro
 *
 * @param {Texto que saldrá en el mensaje de error} param_texto
 */
function MostrarMensajeError(param_texto){

    document.getElementById('textoEmergenteRespuesta').textContent = param_texto;

    document.getElementById('pnlOscurecer'           ).style.visibility = 'visible';
    document.getElementById('pnlRespuestaEmergente'  ).style.visibility = 'visible';
}
