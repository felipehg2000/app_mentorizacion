function chat_selected(id_chat){
    console.log(1)
    var data = {
        _token : csrfToken,
        contact_id: id_chat
    }

    $.ajax({
        url   : url_open_chat,
        method: 'POST'       ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            respuesta.data.forEach(function (mensaje) {
                if (mensaje.SENDER == id_chat){
                    var nuevoDiv = $('<div>', {
                        class: 'pnlMensajeUsuario',
                        text : mensaje.MESSAGE
                    });
                } else {
                    var nuevoDiv = $('<div>', {
                        class: 'pnlMensajeContacto',
                        text : mensaje.MESSAGE
                    });
                }

                console.log(3)
                // Agregar el nuevo div al cuerpo del documento (o al elemento contenedor que desees)
                $('pnlChatDcha').append(nuevoDiv);

            });
        }else {
            window.location.href = url_close;
        }
    })
}
