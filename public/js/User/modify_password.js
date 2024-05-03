function ModifyPassword(){
    var actual_p    = document.getElementById('actual_password').value;
    var nueva_p     = document.getElementById('password'       ).value;
    var nueva_p_rep = document.getElementById('rep_password'   ).value;

    if (actual_p == '' || nueva_p == '' || nueva_p_rep == ''){
        MostrarMensajeError('Error: no puede dejar campos en blanco', true);
        return;
    }

    if (nueva_p != nueva_p_rep){
        MostrarMensajeError('ERROR: los campos de contraseñas deben tener el mismo texto', true);
        document.getElementById('password'       ).value = "";
        document.getElementById('rep_password'   ).value = "";

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
            MostrarMensajeError('Datos modificados correctamente', true);
            document.getElementById('actual_password').value = "";
            document.getElementById('password'       ).value = "";
            document.getElementById('rep_password'   ).value = "";

        } else {
            MostrarMensajeError('ERROR: la contraseña actual no es correcta', true);
            document.getElementById('actual_password').value = "";

            return;
        }
    });
}

function MouseDownActual(){
    document.getElementById('actual_password').type = 'text';
}

function MouseUpActual(){
    document.getElementById('actual_password').type = 'password';
}

function MouseDownNew(){
    document.getElementById('password').type = 'text';
}

function MouseUpNew(){
    document.getElementById('password').type = 'password';
}

function MouseDownRep(){
    document.getElementById('rep_password').type = 'text';
}

function MouseUpRep(){
    document.getElementById('rep_password').type = 'password';
}
//--------------------------------------------------------------------------------------------------
