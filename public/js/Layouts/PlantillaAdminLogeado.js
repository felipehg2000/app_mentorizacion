/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 13:26:14
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-16 13:29:09
 * @Description: Controlador padre de todas las vistas del tipo de usuario administrador.
 *               Contiene las funciones básicas que comparten todas las vistas.
 */

var admin_id = 0;

/**
 * Constructor de la vista, función que se ejecuta una vez todos los elementos se han cargado en la vista.
 * Llama a la función de búsqueda del controlador de la base de datos para ver si hay puntos de notificación que mostrar.
 */
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

/**
 * Redirigimos la localización de la url a la seleccionada en el menú.
 *
 * @param {Índice que corresponde con la opción de menú seleccionada} index
 */
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

/**
 * Modificamos el color de la opción de menú que estamos mostrando.
 * Mostramos una cubierta si el usuario no puede acceder a esa opción de menú con un mensaje dando una explicación de por qué no puede acceder.
 * Ocultamos los puntos de notificación y llamamos a la función que decide si estos puntos se muestran o no.
 */
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
//--------------------------------------------------------------------------------------------------
/**
 * Muestra el panel del mensaje de error con el texto que se le pasa por parametro
 *
 * @param {Texto que saldrá en el mensaje de error} param_texto
 */
function MostrarMensajeError(param_texto, param_hacer_visible){

    document.getElementById('textoEmergenteRespuesta').textContent = param_texto;

    if (param_hacer_visible) {
        document.getElementById('pnlRespuestaEmergente').style.top = '40px';
        document.getElementById('pnlRespuestaEmergente').style.visibility= 'visible';
        document.getElementById('pnlRespuestaEmergente').classList.add('mostrar_animacion');
        InicializarTemporizadorMensajeAviso();
    } else {
        document.getElementById('pnlRespuestaEmergente').style.visibility= 'hidden';
        document.getElementById('pnlRespuestaEmergente').classList.remove('mostrar_animacion');
    }
}
//--------------------------------------------------------------------------------------------------
/**
 * Inicializa el temporizador y llama a la función que hay que ejecutar después de esperar el tiempo indicado.
 * En este caso cierra el panel del mensaje de error
 */
function InicializarTemporizadorMensajeAviso(){
    setTimeout(function() {
        MostrarMensajeError('', false);
    }, 4000); //Se ejecuta medio segundo después
}
