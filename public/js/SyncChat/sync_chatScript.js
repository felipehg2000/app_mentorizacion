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
                var pnlMensajes = document.getElementById('pnlMensajes');

                console.log(mensaje.SENDER)
                console.log(id_chat)

                if (mensaje.SENDER != id_chat){
                    console.log('Entra en if')
                    //Crear el div del mensaje a la derecha
                    var pnlDcha = document.createElement('div');
                    var txtDcha = document.createElement('p'  );

                    pnlDcha.classList.add('pnlMensajeUsuario');
                    pnlDcha.id        = 'pnlMensajeUsuario_' + mensaje.id;

                    txtDcha.classList.add('mensajeUsuario');
                    txtDcha.id          = 'mensajeUsuario_' + mensaje.id;
                    txtDcha.textContent = mensaje.MESSAGE;

                    pnlMensajes.appendChild(pnlDcha);
                    pnlDcha    .appendChild(txtDcha);
                } else {
                    console.log('Entra en else')
                    //Crear el div del mensaje a la izquierda
                    var pnlIzq = document.createElement('div');
                    var txtIzq = document.createElement('p'  );

                    pnlIzq.classList.add('pnlMensajeContacto');
                    pnlIzq.id    = 'pnlMensajeContacto_' + mensaje.id;

                    txtIzq.classList.add('mensajeContacto');
                    txtIzq.id          = 'mensajeContacto_' + mensaje.id;
                    txtIzq.textContent = mensaje.MESSAGE;

                    pnlMensajes.appendChild(pnlIzq);
                    pnlIzq     .appendChild(txtIzq);
                }
            });
        }else {
            window.location.href = url_close;
        }
    })
}
