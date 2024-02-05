function chat_selected(id_chat){
    console.log('Chat_selected 1')
    var data = {
        _token : csrfToken,
        contact_id: id_chat
    }

    $.ajax({
        url   : url_open_chat,
        method: 'POST'       ,
        data  : data
    }).done(function(respuesta){
        console.log('Chat_selected 2')

        if (respuesta.success){
            respuesta.data.forEach(function (mensaje) {

                var pnlMensajes = document.getElementById('pnlMensajes');
                if (mensaje.SENDER == id_chat){
                    //Crear el div del mensaje a la derecha
                    var pnlDcha = document.createElement('div');
                    var txtDcha = document.createElement('p'  );

                    pnlDcha.id        = 'pnlMensajeUsuario_' + mensaje.ID;
                    pnlDcha.className = 'pnlMensajeUsuario';

                    txtDcha.id          = 'mensajeUsuario_' + mensaje.ID;
                    txtDcha.className   = 'mensajeUsuario';
                    txtDcha.textContent = mensaje.MESSAGE;

                    pnlMensajes.appendChild(pnlDcha);
                    pnlDcha    .appendChild(txtDcha);
                } else {
                    //Crear el div del mensaje a la izquierda
                    var pnlIzq = document.createElement('div');
                    var txtIzq = document.createElement('p'  );

                    pnlIzq.id    = 'pnlMensajeContacto_' + mensaje.ID;
                    pnlIzq.class = 'pnlMensajeContacto';

                    txtIzq.id          = 'mensajeContacto_' + mensaje.ID;
                    txtIzq.class       = 'mensajeContacto';
                    txtIzq.textContent = mensaje.MESSAGE;

                    pnlMensajes.appendChild(pnlIzq);
                    pnlIzq     .appendChild(txtIzq);
                }

                console.log(3)
            });
        }else {
            window.location.href = url_close;
        }
    })
}
