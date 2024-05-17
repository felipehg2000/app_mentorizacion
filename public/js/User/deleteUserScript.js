/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 13:17:48
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-17 13:39:52
 * @Description: Controlador asociado a la vista delete de los usuarios mentor y estudiante
 */

/**
 * Abrimos un panel emergente para solicitar que introduzcan la contraseña de la cuenta
 */
function abrirPnlEmergente(){
    document.getElementById("edtPnlEmergente"  ).value = '';

    document.getElementById("pnlOscurecer"     ).style.visibility = "visible";
    document.getElementById("pnlEmergente"     ).style.visibility = "visible";
    document.getElementById("edtPnlEmergente"  ).style.visibility = "visible";
    document.getElementById("textoEmergente").innerText = "Esta acción requiere que introduzca su contraseña para asegurar que quiere eliminar su cuenta.";
}

/**
 * Se comprueba la contraseña con la del usuario en el controlador.
 *  Si es correcta se muestra otro emergente
 *  Si no es correcta se muestra mensaje de error
 */
function aceptarEmergente() {
    var datos = {
        _token  : csrfToken                                       ,
        password: document.getElementById("edtPnlEmergente").value
    }

    $.ajax({
        url   : url_check,
        method: 'POST'   ,
        data  : datos
    }).done(function(respuesta){
        if (respuesta.success) {
            document.getElementById("btnEmergenteCancelar"        ).click();
            document.getElementById("pnlOscurecer"                ).style.visibility = "visible";
            document.getElementById("pnlEmergenteEspecificoDelete").style.visibility = "visible";
        } else{
            document.getElementById('btnEmergenteCancelar'   ).click();

            MostrarMensajeError('La contraseña es incorrecta por lo que no puede borrar la cuenta', true);

            document.getElementById("edtPnlEmergente").value = "";
        }
    });
}

/**
 * Llamamos a la función del controlador que borra el usuario.
 */
function aceptarEmergenteEspecifico(){
    var datos = {_token: csrfToken};
    $.ajax({
        url   : url_delete,
        method: 'POST'    ,
        data  : datos
    }).done(function(respuesta){
        if (respuesta.success){
            document.getElementById('btnEmergenteCancelar'   ).click();

            window.location.href = url_home;
        } else {
            MostrarMensajeError('Ha ocurrido algún tipo de error y no se ha podido borrar el usuario, intentelo más tarde', true);
        }
    })
}

/**
 * Se oculta el panel del segundo emergente.
 */
function cerrarEmergenteEspecifico() {
    document.getElementById("pnlEmergenteEspecificoDelete").style.visibility = "hidden";
    document.getElementById("pnlOscurecer"                ).style.visibility = "hidden";
}
