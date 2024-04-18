var channel2;

var pusher2 = new Pusher('7b7c6d7f8ba7188308b6', {
    cluster: 'eu'
});

document.addEventListener('DOMContentLoaded', function() {
    var id_channel = document.getElementById('id_tuto' ).textContent;
    var tipo_usu   = document.getElementById('tipo_usu').textContent;
    var id_usuario = document.getElementById('id_user' ).textContent;

    channel2 = pusher2.subscribe('tut_access_' + id_channel + id_usuario);

    channel2.bind('App\\Events\\TutUpdateEvent', function(data) {
        if (tipo_usu == '1') {
            document.getElementById('textAreaMentor').value = data.data;
        } else if(tipo_usu == '2'){
            document.getElementById('textAreaEstudiante').value = data.data;
        }
    });
})


/**function ActivarPizarraClick(){
    if (document.getElementById('pnlPizarra').style.visibility == 'visible'){
        document.getElementById('pnlPizarra').style.visibility = 'hidden';
        document.getElementById('btnPizarra').textContent = 'Activar Pizarra';
    } else {
        document.getElementById('pnlPizarra').style.visibility = 'visible';
        document.getElementById('btnPizarra').textContent = 'Desactivar Pizarra';
    }
}*/

function MentorPulsaTecla(){
    var tipo_usu = document.getElementById('tipo_usu').textContent;

    if (tipo_usu == '1') {
        NoGrrabarTeclaExceptoCopiar();
    } else if(tipo_usu == '2') {
        ultimaPulsacion = Date.now();

        setTimeout(function() {
            if (Date.now() - ultimaPulsacion >= 300) {
                var texto = document.getElementById('textAreaMentor').value;
                MandarTexto(texto);
            }
        }, 1000); // Espera un segundo
    }
}

function EstudiantePulsaTecla(){
        var tipo_usu = document.getElementById('tipo_usu').textContent;

        if (tipo_usu == '1') {
            ultimaPulsacion = Date.now();

            setTimeout(function() {
                if (Date.now() - ultimaPulsacion >= 300) {
                    var texto = document.getElementById('textAreaEstudiante').value;
                    MandarTexto(texto);
                }
            }, 1000); //Espera un segundo

        } else if(tipo_usu == '2') {
            NoGrrabarTeclaExceptoCopiar();
        }
}

function NoGrrabarTeclaExceptoCopiar(){
    var codigoTecla = event.keyCode || event.which;

    if (codigoTecla != 67 && (!event.ctrlKey || !event.metaKey)) {
        event.preventDefault();
    }
}

function MandarTexto(param_texto){
    var id_channel = document.getElementById('id_tuto' ).textContent;

    var data = {
        _token     : csrfToken,
        id_channel : id_channel,
        texto      : param_texto
    }

    $.ajax({
        url   : url_send_text,
        method: 'POST',
        data  : data
    });
}
