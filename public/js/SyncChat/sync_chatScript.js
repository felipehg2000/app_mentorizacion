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

        }else {
            window.location.href = url_close;
        }
    })
}

function SendMessage(){
    var message_tmp = document.getElementById('edtMensajeChat'       ).value;
    var id_chat_sel = document.getElementById('lblIdChatSeleccionado').textContent;

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
    var friendCards = document.querySelectorAll('.friend_card');

    friendCards.forEach(function(card) {
        var hiddenIdElement = card.querySelector('.friend_card_hidden_id');
        var friendId        = parseInt(hiddenIdElement.textContent);

        if (friendId === param_id_selected) {
            card.style.backgroundColor = 'white';
        }
    });
}
