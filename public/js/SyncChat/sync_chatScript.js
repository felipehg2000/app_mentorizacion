/**
 *
 * @param {Id del usuario del que vamos a abrir el chat} id_chat
 */
function chat_selected(id_chat){
    var data = {
        _token    : csrfToken,
        contact_id: id_chat
    }

    $.ajax({
        url   : url_open_chat,
        method: 'POST'       ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            EmptyPnael('pnlMensajes');
            respuesta.data.forEach(function (mensaje) {
                if (mensaje.SENDER != id_chat){
                    PushMessage(mensaje.id, mensaje.MESSAGE, 'pnlMensajeUsuario', 'mensajeUsuario');
                } else {
                    PushMessage(mensaje.id, mensaje.MESSAGE, 'pnlMensajeContacto', 'mensajeContacto');
                }
            });

            ModifyBackgroundColorUserSelected(id_chat);

            document.getElementById('lblUsuSeleccionado')   .textContent = respuesta.selec_user.NAME + ' ' + respuesta.selec_user.SURNAME;
            document.getElementById('lblIdChatSeleccionado').textContent = id_chat;

            document.getElementById('pnlSobreponerChatDcha').style.visibility = 'hidden' ;
            document.getElementById('pnlChatDcha'          ).style.visibility = 'visible';
            scrollAlFinal();

        }else {
            window.location.href = url_close;
        }
    })
}

function SendMessage(){
    var message_tmp = document.getElementById('edtMensajeChat'       ).value;
    var id_chat_sel = document.getElementById('lblIdChatSeleccionado').textContent;
    document.getElementById('edtMensajeChat').value = '';

    var data = {
        _token : csrfToken  ,
        datos  : {
            message: message_tmp,
            id_chat: id_chat_sel
        }
    }

    $.ajax({
        url   : url_send_message,
        method: 'POST'          ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            PushMessage(respuesta.mi_id, message_tmp, 'pnlMensajeUsuario', 'mensajeUsuario');
        }else{
            window.location.href = url_close;
        }
    });
}


/**
 * @param {Nombre del panel del que queremos borrar todo su contenido} param_div_name
 */
function EmptyPnael(param_div_name){
    var panel = document.getElementById(param_div_name);
    while (panel.firstChild){
        panel.removeChild(panel.firstChild);
    }
}

/**
 * @param {id del usuario que hemos seleccionado, habrá un componente oculto con esta informaicón para acceder al div que hayamos seleccionado} param_id_selected
 */
function ModifyBackgroundColorUserSelected(param_id_selected){
    var panel_padre = document.getElementById('pnlContactosChatIzq');

    for (var i = 0; i < panel_padre.children.length; i++) {
        var panel_hijo = panel_padre.children[i];
        panel_hijo.classList.remove('selected');
    }

    var friend_id = document.getElementById('friend_card_hidden_' + param_id_selected);
    friend_id.classList.add('selected');
}
