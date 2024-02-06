/**
 *
 * @param {Id del usuario del que vamos a abrir el chat} id_chat
 */
function chat_selected(id_chat){
    console.log('Chat_selected 1')

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
            respuesta.data.forEach(function (mensaje) {

                console.log(mensaje.SENDER)
                console.log(id_chat)

                if (mensaje.SENDER != id_chat){
                    console.log('Entra en if')
                    //Crear el div del mensaje a la derecha
                    PushMessage(mensaje.id, mensaje.MESSAGE, 'pnlMensajeUsuario', 'mensajeUsuario');
                } else {
                    console.log('Entra en else')
                    PushMessage(mensaje.id, mensaje.MESSAGE, 'pnlMensajeContacto', 'mensajeContacto');
                }
            });
        }else {
            window.location.href = url_close;
        }
    })
}

function SendMessage(){
    var message_tmp = document.getElementById('edtMensajeChat').value;

    var data = {
        _token    : csrfToken,
        message   : message_tmp
    }

    $.ajax({
        url   : url_send_message,
        method: 'POST'          ,
        data  : data
    }).done(function(respuesta){
        console.log(respuesta)
        if (respuesta.success){
            document.getElementById('edtMensajeChat').value = '';
            PushMessage(respuesta.mi_id, message_tmp, 'pnlMensajeUsuario', 'mensajeUsuario');
        }else{
            window.location.href = url_close;
        }
    });
}

/**
 *
 * @param {Se utilizara para que el id de cada mensaje sea distinto                             } param_id_chat
 * @param {El texto que se mostrará en el mensaje                                               } param_message
 * @param {Nombre que se le pondrá a la clase del panel, sumado al id se creará el nombre del id} param_name_pnl
 * @param {Nombre que se le pondrá a la clase del label, sumado al id se creará el nombre del id} param_name_lbl
 */
function PushMessage(param_id_chat, param_message, param_name_pnl, param_name_lbl){
    console.log('PUSH MESSAGE')
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
}
