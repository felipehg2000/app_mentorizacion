function redirection(index) {
    switch(index){
        case 1:
            window.location.href = url_tablon_completo;
            break;
        case 2:
            window.location.href = url_tareas_completadas;
            break;
        case 3:
            window.location.href = url_tareas_a_completar;
            break;
        case 4:
            window.location.href = url_chats_privados
            break;
        case 5:
            window.location.href = url_informacion;
            break;
        case 6:
            window.location.href = url_solicitudes;
            break;
        case 7:
            window.location.href = url_acceso_a_tutoria;
            break;
        case 8:
            window.location.href = url_amigos_actuales;
            break;
        case 9:
            window.location.href = url_solicitudes_de_amistad;
            break;
        case 10:
            window.location.href = url_modificar_mis_datos;
            break;
        case 11:
            window.location.href = url_eliminar_mi_cuenta;
            break;
        case 12:
            window.location.href = url_cerrar_sesion;
            break;
        case 13:
            window.location.href = url_novedades;
            break;
    }
}

function cerrarEmergetne(){
    document.getElementById("pnlOscurecer"   ).style.visibility = "hidden";
    document.getElementById("pnlEmergente"   ).style.visibility = "hidden";
    document.getElementById("edtPnlEmergente").style.visibility = "hidden";
}

function aceptarPnlRespuestaEmergente(){
    document.getElementById("pnlOscurecer"            ).style.visibility = "hidden" ;
    document.getElementById("pnlRespuestaEmergente"   ).style.visibility = "hidden" ;
    document.getElementById('btnAceptarEmergente'     ).style.visibility = "visible";

    document.getElementById('btnCancelarEmergente').innerText = "Cancelar" ;
}

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
            CambiarOpcionDeColoresYMostrarCubierta(respuesta.user_type, respuesta.tiene_sala_estudio, respuesta.num_alumnos);
        } else {
            window.location.href = url_close;
        }
    });
})

function CambiarOpcionDeColoresYMostrarCubierta(param_user_type, param_tiene_sala_estudio, param_num_alumnos){
    var url_actual = window.location.href;
    var id_elemento = '';
    var mostrarCubierta = false;
    var mensajeMuchosAlumnos = false;
    var mensajeYaSalaEstudio = false;

    if (url_actual == url_tablon_completo){
        id_elemento = "submenu_1";
        if (param_user_type == 1 && !param_tiene_sala_estudio){
            mostrarCubierta = true;
        }
    } else if (url_actual == url_tareas_completadas){
        id_elemento = "submenu_2";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_tareas_a_completar){
        id_elemento = "submenu_3";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_chats_privados){
        id_elemento = "submenu_4";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_informacion){
        id_elemento = "submenu_5";
    }else if (url_actual == url_solicitudes){
        id_elemento = "submenu_6";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_acceso_a_tutoria){
        id_elemento = "submenu_7";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_amigos_actuales){
        id_elemento = "submenu_8";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_solicitudes_de_amistad){
        id_elemento = "submenu_9";
        if (param_user_type == 1 && param_tiene_sala_estudio){
            mostrarCubierta = true;
            mensajeYaSalaEstudio = true;
        }
        if (param_user_type == 2 && param_num_alumnos == 5){
            mostrarCubierta = true;
            mensajeMuchosAlumnos = true;
        }
    }else if (url_actual == url_modificar_mis_datos){
        id_elemento = "submenu_10";
    }else if (url_actual == url_eliminar_mi_cuenta){
        id_elemento = "submenu_11";
    }else if (url_actual == url_novedades){
        id_elemento = "submenu_13";
    }

    if (id_elemento != '') {
        document.getElementById(id_elemento).style.backgroundColor= "white";
        document.getElementById(id_elemento).style.color          = "black";
        document.getElementById(id_elemento).style.fontWeight     = "bold" ;
    }

    if (mostrarCubierta){
        document.getElementById('pnlCubierta'       ).style.visibility = 'visible';
        document.getElementById('pnlCubiertaMensaje').style.visibility = 'visible';

        var mensaje = '';
        if (param_user_type == 1) {
            if (mensajeYaSalaEstudio){
                mensaje = 'No puedes seguir a más tutores hasta que no abandnes la sala de estudio a la que perteneces.';
            }

            mensaje = 'Debe pertenecer a una sala de estudio para acceder a esta opción';
        } else if(param_user_type == 2){
            if (mensajeMuchosAlumnos){
                mensaje = 'Tiene el límite de alumnos permitidos, no puede aceptar más solicitudes de amistad';
            }

            mensaje = 'Debe tener alumnos en su sala de estudios para acceder a esta opción'
        }

        document.getElementById('pnlCubiertaMensaje').textContent = mensaje;

    } else {
        document.getElementById('pnlCubierta'       ).style.visibility = 'hidden';
        document.getElementById('pnlCubiertaMensaje').style.visibility = 'hidden';
    }

    document.getElementById('pnlCarga').style.visibility = 'hidden';
}
