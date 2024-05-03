function abrirPnlEmergente(){
    document.getElementById("pnlOscurecer"     ).style.visibility = "visible";
    document.getElementById("pnlEmergente"     ).style.visibility = "visible";
    document.getElementById("edtPnlEmergente"  ).style.visibility = "visible";
    document.getElementById("textoEmergente").innerText = "Esta acción requiere que introduzca su contraseña para asegurar que quiere eliminar su cuenta.";
}

function aceptarEmergente() {
    var datos = {
        _token  : csrfToken                                       ,
        password: document.getElementById("edtPnlEmergente").value
    }

    $.ajax({
        url   : url_check,
        method: 'POST'   ,
        data  : datos
    }).done(function(respuesta){
        if (respuesta.success) {
            document.getElementById("btnEmergenteCancelar"        ).click();
            document.getElementById("pnlOscurecer"                ).style.visibility = "visible";
            document.getElementById("pnlEmergenteEspecificoDelete").style.visibility = "visible";
        } else{
            document.getElementById('btnEmergenteCancelar'   ).click();

            MostrarMensajeError('La contraseña es incorrecta por lo que no puede borrar la cuenta', true);

            document.getElementById("edtPnlEmergente").value = "";
        }
    });
}

function aceptarEmergenteEspecifico(){
    var datos = {_token: csrfToken};
    $.ajax({
        url   : url_delete,
        method: 'POST'    ,
        data  : datos
    }).done(function(respuesta){
        if (respuesta.success){
            document.getElementById('btnEmergenteCancelar'   ).click();

            window.location.href = url_home;
        } else {
            MostrarMensajeError('Ha ocurrido algún tipo de error y no se ha podido borrar el usuario, intentelo más tarde', true);
        }
    })
}

function cerrarEmergenteEspecifico() {
    document.getElementById("pnlEmergente"  ).style.visibility = "hidden";
    document.getElementById("panelOscurecer").style.visibility = "hidden";
}
