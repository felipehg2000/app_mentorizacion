document.addEventListener('DOMContentLoaded', function() {
    var data = {
        _token : csrfToken,
    }

    CambiarOpcionDeColoresYMostrarCubierta();
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
    }
}

function CambiarOpcionDeColoresYMostrarCubierta(){
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
    }else if (url_actual == url_cerrar_sesion){
        id_elemento = "submenu_7";
    }

    if (id_elemento != '') {
        document.getElementById(id_elemento).style.backgroundColor= "white";
        document.getElementById(id_elemento).style.color          = "black";
        document.getElementById(id_elemento).style.fontWeight     = "bold" ;
    }

    document.getElementById('pnlCarga').style.visibility = 'hidden';
}
