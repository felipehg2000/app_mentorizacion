/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:06:37
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-25 08:53:53
 * @Description: Controlador de la vista tut_request.blade.php
 */

$(document).ready(function(){
    let dataTable = new DataTable('tutoring_table');
});

/**
 * Llamamos a la función MostrarPanel con los argumentos necesarios.
 *
 * @param {Booleano que indica si el usuario logeado es un mentor o un estudiante} param_tipo_usuario
 */
function MostrarNewTutoring(param_tipo_usuario){
    if (param_tipo_usuario == 1) {
        MostrarPanel(false, true);
    } else if (param_tipo_usuario == 2) {
        LimpiarPnlNewTutoring()
        MostrarPanel(true, false);
    }
}

/**
 * Limpia los campos del panel de creación de tareas.
 */
function LimpiarPnlNewTutoring(){
    document.getElementById('input_fecha' ).innerText = '';
    document.getElementById('input_hora'  ).innerText = '';
    document.getElementById('input_estado').values    = '0';
}

/**
 * Muestra el panel de tareas con los campos habilitados o no según el tipo de usuario que esté logeado.
 *
 * @param {Booleano que determina si la fecha y hora son readOnly o no} param_readOnly_fecha_hora
 * @param {Booleano que determina si el estado es readOnly o no} param_readOnly_estado
 */
function MostrarPanel(param_readOnly_fecha_hora, param_readOnly_estado){
    document.getElementById('pnlOscurecer').style.visibility = 'visible';

    document.getElementById('input_fecha' ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_hora'  ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_estado').disabled = param_readOnly_estado    ;

    document.getElementById('new_tutoring').style.visibility = 'visible';
}

/**
 * Oculatmos el panel de los datos específicos de las tareas y mostramos la tabla con todas las tareas creadas.
 */
function MostrarTabla(){
    document.getElementById('pnlOscurecer').style.visibility = 'hidden';
    document.getElementById('new_tutoring').style.visibility = 'hidden';
}

/**
 * Llama a una función del controlador de la base de datos para solicitarle los datos de la tutoría concreta
 * rellena los campos y muestra el panel específico de tareas.
 *
 * @param {Identificador de la tutoría sobre la que hemos interactuado} param_id_tut
 */
function ClickDataTable(param_id_tut){
    document.getElementById('id_tut').textContent = param_id_tut;

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

/**
 * Elige si la tarea hay que crearla o ya está creada y hay que modificar sus datos.
 */
function CrearOModificarNuevaTutoria(){
    var id = document.getElementById('id_tut').textContent;
    if (id == 'Texto oculto'){
        CrearNuevaTutoria();
    } else {
        ModificarTutoria(id);
    }


}

/**
 * Coge los datos del formulario, comprueba que sean válidos y llama al controlador de base de datos para
 * que se encargue de crear una nueva tarea
 *
 * @returns NULL cuando las condiciones no se cumplen, para salir de la función.
 */
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
        MostrarMensajeError(texto, true);
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
            MostrarMensajeError('Tu mentor ya tiene una tutoría concertada ese día. Seleccione otro día', true);
        }
    });
}

/**
 * Coge los dato de la tarea, los valida y llama al controlador de la base de datos para
 * que se encargue de actualizar la tupla de la tarea.
 *
 * @param {Identificador específico de la tarea seleccionada} param_id
 * @returns NULL en caso de que haya un error para salir de la función
 */
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
    }

    if (!datosCorrectos){
        MostrarMensajeError(texto, true);
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

/**
 *
 * @param {Fecha con formato de base de datos} param_fecha
 * @returns fecha formateada para ser leida por el input correspondiente
 */
function FormatearFecha(param_fecha) {
    var fechaOriginal = param_fecha;
    var fechaCompleta = new Date(fechaOriginal);

    var year = fechaCompleta.getFullYear();
    var mes  = ('0' + (fechaCompleta.getMonth() + 1)).slice(-2);
    var day  = ('0' + fechaCompleta.getDate()).slice(-2);

    var fechaFormateada = year + '-' + mes + '-' + day;

    return fechaFormateada
}

/**
 *
 * @param {Hora con formato de base de datos} param_hora
 * @returns Hora formateada para ser leido por el input correspondiente
 */
function FormatearHora(param_hora){
    var partes = param_hora.split(" ");

    var horaCompleta = partes[1];

    var [hora, minuto] = horaCompleta.split(":").slice(0, 2);

    var horaFormateada   = hora  .padStart(2, "0");
    var minutoFormateado = minuto.padStart(2, "0");

    var horaMinuto = horaFormateada + ":" + minutoFormateado;

    return horaMinuto;
}
