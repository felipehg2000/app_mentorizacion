$(document).ready(function(){
    let dataTable = new DataTable('tutoring_table');
});

function MostrarNewTutoring(param_tipo_usuario, param_tutoring_id){
    if (param_tipo_usuario == 1) {
        MostrarPanel(false, true);
    } else if (param_tipo_usuario == 2) {
        LimpiarPnlNewTutoring()
        MostrarPanel(true, false);
    }
}

function LimpiarPnlNewTutoring(){
    document.getElementById('input_fecha' ).innerText = '';
    document.getElementById('input_hora'  ).innerText = '';
    document.getElementById('input_estado').values    = '0';
}

function MostrarPanel(param_readOnly_fecha_hora, param_readOnly_estado){
    document.getElementById('pnlOscurecer').style.visibility = 'visible';

    document.getElementById('input_fecha' ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_hora'  ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_estado').disabled = param_readOnly_estado    ;

    document.getElementById('new_tutoring').style.visibility = 'visible';
}

function MostrarTabla(){
    document.getElementById('pnlOscurecer').style.visibility = 'hidden';
    document.getElementById('new_tutoring').style.visibility = 'hidden';
}

function ClickDataTable(param_id_tut){
    document.getElementById('id_tut').value = param_id_tut;

    var data = {
        _token : csrfToken,
        id     : param_id_tut
    }

    $.ajax({
        url   : url_get_tuto,
        method: 'POST'      ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success) {
            document.getElementById('input_fecha' ).value = FormatearFecha(respuesta.tut_data.DATE);
            document.getElementById('input_hora'  ).value = FormatearHora (respuesta.tut_data.DATE);
            document.getElementById('input_estado').value = respuesta.tut_data.STATUS

            if (respuesta.user_type == 1){
                MostrarPanel(true, true)
            } else if (respuesta.user_type == 2){
                MostrarPanel(true, false);
            }

        } else {
            window.location.href = url_close;
        }
    });
}

function CrearOModificarNuevaTutoria(){
    var id = document.getElementById('id_tut').value;
    if (id == 'Texto oculto'){
        CrearNuevaTutoria();
    } else {
        ModificarTutoria(id);
    }


}

function CrearNuevaTutoria() {
    var fecha_hoy      = new Date();
    var date           = new Date(document.getElementById('input_fecha').value);
    var hourInput      = document.getElementById('input_hora'  ).value;
    var estado         = document.getElementById('input_estado').value;
    var texto          = '';
    var datosCorrectos = true;
    if (date == '' || hourInput == ''){
        texto = 'Para realizar esta acción primero tiene que rellenar todos los campos';
        datosCorrectos = false;
    } else if (date <= fecha_hoy){
        texto = 'La fecha introducida debe ser posterior a la fecha actual';
        datosCorrectos = false;
    }

    if (!datosCorrectos){
        MostrarMensajeError(texto);
        return;
    }

    var data = {
        _token : csrfToken,
        fecha  : FormatearFecha(date),
        hora   : hourInput,
        status : estado
    }

    $.ajax({
        url   : url_add_tuto,
        method: 'POST'      ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            location.reload();
        } else {
            window.location.href = url_close;
        }
    });
}

function ModificarTutoria(param_id){
    var fecha_hoy      = new Date();
    var date           = new Date(document.getElementById('input_fecha').value);
    var hourInput      = document.getElementById('input_hora'  ).value;
    var estado         = document.getElementById('input_estado').value;
    var texto          = '';
    var datosCorrectos = true;
    if (date == '' || hourInput == ''){
        texto = 'Para realizar esta acción primero tiene que rellenar todos los campos';
        datosCorrectos = false;
    } else if (date <= fecha_hoy){
        texto = 'La fecha introducida debe ser posterior a la fecha actual';
        datosCorrectos = false;
    }

    if (!datosCorrectos){
        MostrarMensajeError(texto);
        return;
    }

    var data = {
        _token : csrfToken,
        id     : param_id,
        fecha  : FormatearFecha(date),
        hora   : hourInput,
        status : estado
    }

    $.ajax({
        url   : url_update_tuto,
        method: 'POST'      ,
        data  : data
    }).done(function(respuesta){
        if (respuesta.success){
            location.reload();
        } else {
            window.location.href = url_close;
        }
    });
}

function FormatearFecha(param_fecha) {
    var fechaOriginal = param_fecha;
    var fechaCompleta = new Date(fechaOriginal);

    var year = fechaCompleta.getFullYear();
    var mes  = ('0' + (fechaCompleta.getMonth() + 1)).slice(-2);
    var day  = ('0' + fechaCompleta.getDate()).slice(-2);

    var fechaFormateada = year + '-' + mes + '-' + day;

    return fechaFormateada
}

function FormatearHora(param_hora){
    var partes = param_hora.split(" ");

    var horaCompleta = partes[1];

    var [hora, minuto] = horaCompleta.split(":").slice(0, 2);

    var horaFormateada   = hora  .padStart(2, "0");
    var minutoFormateado = minuto.padStart(2, "0");

    var horaMinuto = horaFormateada + ":" + minutoFormateado;

    return horaMinuto;
}

/**
 * Muestra el panel del mensaje de error con el texto que se le pasa por parametro
 *
 * @param {Texto que saldrá en el mensaje de error} param_texto
 */
function MostrarMensajeError(param_texto){

    document.getElementById('textoEmergenteRespuesta').textContent = param_texto;

    document.getElementById('pnlOscurecer'           ).style.visibility = 'visible';
    document.getElementById('pnlRespuestaEmergente'  ).style.visibility = 'visible';
}
