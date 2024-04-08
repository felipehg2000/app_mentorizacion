function CrearYModificarAdmins() {
    var nombre       = document.getElementById('name'        ).value;
    var apellidos    = document.getElementById('surname'     ).value;
    var email        = document.getElementById('email'       ).value;
    var usuario      = document.getElementById('user'        ).value;
    var password     = document.getElementById('password'    ).value;
    var rep_password = document.getElementById('rep_password').value;
    var description  = document.getElementById('description' ).value;

    var insertar_datos = true;
    var mensaje = "";

    if (window.location.href == url_create_admin){
        if (nombre == "" || email == "" || password == "" || rep_password == ""){
            insertar_datos = false;
            mensaje = "ERROR: todos los campos con * deben estar rellenos";
        }
    } else if (window.location.href == url_modificar_mis_datos) {
        if (nombre == "" || email == ""){
            insertar_datos = false;
            mensaje = "ERROR: todos los campos con * deben estar rellenos";
        }
    } else if (password != rep_password) {
        insertar_datos = false
        mensaje = "ERROR: los campos de contraseñas deben tener el mismo texto";
    }

    if (!insertar_datos){
        MostrarMensajeError(mensaje);
    } else {
        var data = {
            _token      : csrfToken,
            nombre      : nombre   ,
            apellidos   : apellidos,
            email       : email    ,
            usuario     : usuario  ,
            password    : password ,
            description : description
        }

        var url_llamada = "";
        var mensaje_bien = ";"
        if (window.location.href == url_create_admin){
            url_llamada = url_create_admin_store;
            mensaje_bien = "Administrador creado correctamente";
        } else if (window.location.href == url_modificar_mis_datos){
            url_llamada = url_modify_admin_store;
            mensaje_bien = "Datos actualizados correctamente";
        }

        $.ajax({
            url   : url_llamada,
            method: 'POST'     ,
            data  : data
        }).done(function(respuesta){
            if(respuesta.success){
                alert(mensaje_bien)
                if (window.location.href == url_create_admin){
                    document.getElementById('name'        ).value = "";
                    document.getElementById('surname'     ).value = "";
                    document.getElementById('email'       ).value = "";
                    document.getElementById('user'        ).value = "";
                    document.getElementById('password'    ).value = "";
                    document.getElementById('rep_password').value = "";
                    document.getElementById('description' ).value = "";
                }
            } else {
                texto = 'No se han encontrado los datos a modificar';
                alert(param_texto);
            }
        });
    }
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