var channel;

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('7b7c6d7f8ba7188308b6', {
    cluster: 'eu'
});

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
            channel = pusher.subscribe('sync_chat_' + respuesta.user_id);

            channel.bind('App\\Events\\NewMessageEvent', function(data) {
                if(window.location.href == url_chats_privados){
                    var id_usuario_seleccionado = document.getElementById('lblIdChatSeleccionado').textContent;

                    if (respuesta.user_type == 1 && id_usuario_seleccionado == data.data.mi_id){ //Estudiante
                        PushMessage(data.data.message_id, data.data.mensaje, 'pnlMensajeContacto', 'mensajeContacto');
                    } else if (respuesta.user_type == 2 && id_usuario_seleccionado == data.data.mi_id) {
                        PushMessage(data.data.message_id, data.data.mensaje, 'pnlMensajeContacto', 'mensajeContacto');
                    }
                }

                VisibilidadNotificacionNuevoMensaje(true);
                InicializarTemporizador();
            });

            if (respuesta.new_messages){
                document.getElementById('notification_4').style.visibility = 'visible';
            }

            if (respuesta.new_friend_requests){
                document.getElementById('notification_9').style.visibility = 'visible';
            }

            if (respuesta.new_tasks){
                document.getElementById('notification_1').style.visibility = 'visible';
            }

            if (respuesta.new_answer){
                document.getElementById('notification_1').style.visibility = 'visible';
            }

            if (respuesta.new_tutoring){
                document.getElementById('notification_6').style.visibility = 'visible';
            }

            CambiarOpcionDeColoresYMostrarCubierta(respuesta.user_type, respuesta.tiene_sala_estudio, respuesta.num_alumnos, respuesta.solicitud_mandada);
        } else {
            window.location.href = url_close;
        }
    });
})

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
        case 14:
            window.location.href = url_change_password;
            break;
        case 15:
            window.location.href = url_change_porfile_img;
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

function CambiarOpcionDeColoresYMostrarCubierta(param_user_type, param_tiene_sala_estudio, param_num_alumnos, param_solicitud_mandada){
    var url_actual              = window.location.href;
    var id_elemento             = ''   ;
    var id_div_notificacion     = ''   ;
    var mostrarCubierta         = false;
    var mensajeMuchosAlumnos    = false;
    var mensajeYaSalaEstudio    = false;
    var mensajeAmigosActuales   = false;
    var mensajeNoHora           = false;
    var mensajeNoUsuarios       = false;
    var mensajeSolicitudMandada = false;
    var url_opcion_seleccionada = "";

    if (url_actual == url_tablon_completo){
        id_elemento = "submenu_1";
        url_opcion_seleccionada = url_task_saw;
        id_div_notificacion = "notification_1";
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
        id_div_notificacion = "notification_6";
        url_opcion_seleccionada = url_tut_saw;
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        }
    }else if (url_actual == url_acceso_a_tutoria){
        id_elemento = "submenu_7";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio) || (param_user_type == 3 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
        } else if (document.getElementById('tit_tut_access').innerText == ''){
            mostrarCubierta = true;
            mensajeNoHora   = true;
        }
    }else if (url_actual == url_amigos_actuales){
        id_elemento = "submenu_8";
        if ((param_user_type == 1 && !param_tiene_sala_estudio) || (param_user_type == 2 && !param_tiene_sala_estudio)){
            mostrarCubierta = true;
            mensajeAmigosActuales = true;
        }
    }else if (url_actual == url_solicitudes_de_amistad){
        id_elemento = "submenu_9";
        id_div_notificacion = "notification_9";
        url_opcion_seleccionada = url_friend_req_saw;
        if (param_user_type == 1 && param_tiene_sala_estudio){
            mostrarCubierta = true;
            mensajeYaSalaEstudio = true;
        }
        if (param_user_type == 1 && param_solicitud_mandada){
            mostrarCubierta = true;
            mensajeSolicitudMandada = true;
        }
        if (param_user_type == 2 && param_num_alumnos == 5){
            mostrarCubierta = true;
            mensajeMuchosAlumnos = true;
        }
        if (document.getElementById('friendship_title').innerText == ''){
            mostrarCubierta = true;
            mensajeNoUsuarios = true;
        }
    }else if (url_actual == url_modificar_mis_datos){
        id_elemento = "submenu_10";
    }else if (url_actual == url_eliminar_mi_cuenta){
        id_elemento = "submenu_11";
    }else if (url_actual == url_novedades){
        id_elemento = "submenu_13";
    }else if (url_actual == url_change_password){
        id_elemento = "submenu_14";
    }else if (url_actual == url_change_porfile_img){
        id_elemento = "submenu_15";
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
                mensaje = 'No puede seguir a más tutores hasta que no abandne la sala de estudio a la que pertenece.';
            } else if (mensajeAmigosActuales){
                mensaje = 'No pertenece a ninguna sala de estudios aún.'
            }else if(mensajeNoHora){
                mensaje = 'Para acceder a una tutoría debe tenerla programada, que esté aceptada y que sea la hora de la tutoría o una hora posterior a la concretada.';
            }else if (mensajeNoUsuarios){
                mensaje = 'No tiene ninguna solicitud pendiente.';
            }else if (param_solicitud_mandada){
                mensaje = 'Tiene una solicitud de amistad mandada y pendiente, no puede mandar más solicitudes de amsitad';
            }else{
                mensaje = 'Debe pertenecer a una sala de estudio para acceder a esta opción.';
            }
        } else if(param_user_type == 2){
            if (mensajeMuchosAlumnos){
                mensaje = 'Tiene el límite de alumnos permitidos, no puede aceptar más solicitudes de amistad.';
            } else if (mensajeAmigosActuales){
                mensaje = 'No tiene ningún contacto aún.'
            }else if(mensajeNoHora){
                mensaje = 'Para acceder a una tutoría debe tenerla programada, que esté aceptada y que sea la hora de la tutoría o una hora posterior a la concretada.';
            }else if (mensajeNoUsuarios){
                mensaje = 'No tiene ninguna solicitud pendiente.';
            }else {
                mensaje = 'Debe tener alumnos en su sala de estudios para acceder a esta opción.';
            }
        }

        document.getElementById('pnlCubiertaMensaje').textContent = mensaje;

    } else {
        document.getElementById('pnlCubierta'       ).style.visibility = 'hidden';
        document.getElementById('pnlCubiertaMensaje').style.visibility = 'hidden';
    }

    if (url_opcion_seleccionada != ""){
        var data = {
            _token : csrfToken,
        }

        $.ajax({
            url   : url_opcion_seleccionada,
            method: 'POST',
            data  : data
        }).done(function(respuesta){
            if(respuesta.success){
                document.getElementById(id_div_notificacion).style.visibility = 'hidden';
            }
        });
    }

    document.getElementById('pnlCarga').style.visibility = 'hidden';
}

function VisibilidadNotificacionNuevoMensaje(param_hacer_visible) {
    if (param_hacer_visible) {
        document.getElementById('pnlNotificacionMensajeNuevo').style.visibility= 'visible';
    } else {
        document.getElementById('pnlNotificacionMensajeNuevo').style.visibility= 'hidden';
    }
}

function InicializarTemporizador(){
    setTimeout(function() {
        VisibilidadNotificacionNuevoMensaje(false);
    }, 4000); //Se ejecuta después de 4 segundos
}

function InicializarTemporizadorTutoria(){
    setTimeout(function() {
        VisibilidadNotificacionNuevoMensaje(false);
    }, 500); //Se ejecuta medio segundo después
}

/**
 *
 * @param {Se utilizara para que el id de cada mensaje sea distinto                             } param_id_chat
 * @param {El texto que se mostrará en el mensaje                                               } param_message
 * @param {Nombre que se le pondrá a la clase del panel, sumado al id se creará el nombre del id} param_name_pnl
 * @param {Nombre que se le pondrá a la clase del label, sumado al id se creará el nombre del id} param_name_lbl
 */
function PushMessage(param_id_chat, param_message, param_name_pnl, param_name_lbl){
    var pnlMensajes = document.getElementById('pnlMensajes');
    var pnl         = document.createElement ('div'        );
    var txt         = document.createElement ('p'          );

    pnl.classList.add(param_name_pnl);
    pnl.id        = param_name_pnl + '_' + param_id_chat;

    txt.classList.add(param_name_lbl);
    txt.id          = param_name_lbl + '_' + param_id_chat;
    txt.textContent = param_message;

    pnlMensajes.appendChild(pnl);
    pnl        .appendChild(txt);

    scrollAlFinal();
}

function scrollAlFinal() {
    if (document.getElementById('pnlSobreponerChatDcha').style.visibility == 'hidden'){
        var panel = $('#pnlMensajes')[0]; // Obtenemos el elemento DOM
        panel.scrollTop = panel.scrollHeight; // Hacemos scroll al final
    }
}
