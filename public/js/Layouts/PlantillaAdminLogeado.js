var admin_id = 0;

document.addEventListener('DOMContentLoaded', function() {
    var data = {
        _token : csrfToken,
    }

    $.ajax({
        url   : url_datos_inicio,
        method: 'POST',
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            //Tiene que ir antes de la función CambiarOpcionDeColoresYMostrarCubierta, en esta función se esconde en caso de que entremos en esta opción
            if (respuesta.new_report_request){
                document.getElementById('notification_10').style.visibility = 'visible';
            }

            admin_id = respuesta.admin_id;
            CambiarOpcionDeColoresYMostrarCubierta();
        }
    });
})

function redirection(index) {
    switch(index){
        case 1:
            window.location.href = url_bloquear_mentores;
            break;
        case 2:
            window.location.href = url_bloquear_estudiantes;
            break;
        case 3:
            window.location.href = url_primeros_pasos;
            break;
        case 4:
            window.location.href = url_novedades
            break;
        case 5:
            window.location.href = url_modificar_mis_datos;
            break;
        case 6:
            window.location.href = url_eliminar_mi_cuenta;
            break;
        case 7:
            window.location.href = url_cerrar_sesion;
            break;
        case 8:
            window.location.href = url_create_admin;
            break;
        case 9:
            window.location.href = url_bloquear_admins;
            break;
        case 10:
            window.location.href = url_reports;
            break;
        case 11:
            window.location.href = url_change_porfile_img;
            break;
    }
}

function CambiarOpcionDeColoresYMostrarCubierta(){
    var mostrarCubierta = false;
    var url_actual = window.location.href;

    if (url_actual == url_bloquear_mentores){
        id_elemento = "submenu_1";
    } else if (url_actual == url_bloquear_estudiantes){
        id_elemento = "submenu_2";
    }else if (url_actual == url_primeros_pasos){
        id_elemento = "submenu_3";
    }else if (url_actual == url_novedades){
        id_elemento = "submenu_4";
    }else if (url_actual == url_modificar_mis_datos){
        id_elemento = "submenu_5";
    }else if (url_actual == url_eliminar_mi_cuenta){
        id_elemento = "submenu_6";
        if (admin_id != 1){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_cerrar_sesion){
        id_elemento = "submenu_7";
    }else if (url_actual == url_create_admin){
        id_elemento = "submenu_8";
        if (admin_id != 1){
            mostrarCubierta = true;
        }
    }else if(url_actual == url_bloquear_admins){
        id_elemento = "submenu_9";
        if (admin_id != 1){
            mostrarCubierta = true;
        }
    }else if(url_actual == url_reports){
        id_elemento = "submenu_10";

        var data = {
            _token : csrfToken,
        }

        $.ajax({
            url   : url_report_saw,
            method: 'POST',
            data  : data
        }).done(function(respuesta){
            if(respuesta.success){
                document.getElementById('notification_10').style.visibility = 'hidden';
            }
        });
    }else if(url_actual == url_change_porfile_img){
        id_elemento = "submenu_11";
    }

    if (id_elemento != '') {
        document.getElementById(id_elemento).style.backgroundColor= "white";
        document.getElementById(id_elemento).style.color          = "black";
        document.getElementById(id_elemento).style.fontWeight     = "bold" ;
    }

    if (mostrarCubierta){
        document.getElementById('pnlCubierta'       ).style.visibility = 'visible';
        document.getElementById('pnlCubiertaMensaje').style.visibility = 'visible';

        var mensaje = 'A esta opción solo puede acceder el superadmin';
        document.getElementById('pnlCubiertaMensaje').textContent = mensaje;

    } else {
        document.getElementById('pnlCubierta'       ).style.visibility = 'hidden';
        document.getElementById('pnlCubiertaMensaje').style.visibility = 'hidden';
    }

    document.getElementById('pnlCarga').style.visibility = 'hidden';
}

function aceptarPnlRespuestaEmergente(){
    document.getElementById("pnlOscurecer"            ).style.visibility = "hidden" ;
    document.getElementById("pnlRespuestaEmergente"   ).style.visibility = "hidden" ;
    document.getElementById('btnAceptarEmergente'     ).style.visibility = "visible";

    document.getElementById('btnCancelarEmergente').innerText = "Cancelar" ;
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
//--------------------------------------------------------------------------------------------------
