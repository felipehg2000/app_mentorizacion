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
            window.log(2);
        }else {
            window.location.href = url_close;
        }
    })
}
