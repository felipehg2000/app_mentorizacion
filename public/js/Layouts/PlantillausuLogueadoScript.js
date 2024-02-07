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
    var url_actual = window.location.href;
    var id_elemento = '';

    if (url_actual == url_tablon_completo){
        id_elemento = "submenu_1";
    } else if (url_actual == url_tareas_completadas){
        id_elemento = "submenu_2";
    }else if (url_actual == url_tareas_a_completar){
        id_elemento = "submenu_3";
    }else if (url_actual == url_chats_privados){
        id_elemento = "submenu_4";
    }else if (url_actual == url_informacion){
        id_elemento = "submenu_5";
    }else if (url_actual == url_solicitudes){
        id_elemento = "submenu_6";
    }else if (url_actual == url_acceso_a_tutoria){
        id_elemento = "submenu_7";
    }else if (url_actual == url_amigos_actuales){
        id_elemento = "submenu_8";
    }else if (url_actual == url_solicitudes_de_amistad){
        id_elemento = "submenu_9";
    }else if (url_actual == url_modificar_mis_datos){
        id_elemento = "submenu_10";
    }else if (url_actual == url_eliminar_mi_cuenta){
        id_elemento = "submenu_11";
    }else if (url_actual == url_cerrar_sesion){
        id_elemento = "submenu_12";
    }

    document.getElementById(id_elemento).style.backgroundColor= "white";
    document.getElementById(id_elemento).style.color          = "black";
    document.getElementById(id_elemento).style.fontWeight     = "bold" ;
})
