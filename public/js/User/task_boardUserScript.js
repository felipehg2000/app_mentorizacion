/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 20:42:20
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-21 17:35:42
 * @Description: Controlador de la vista layout.tasks y sus descendientes. Controla todos los procesos que
 *               desarrollamos en la vista.
 */

//--------------------------------------------------------------------------------------------------
/**
 * Rellena los campos del formulario y en caso de que sea el estudiante deshabilita los campos para que
 * no pueda modificarlos y solo pueda hacer la entrega. En caso del mentor lo deja habilitado para modificar.
 *
 * @param {Dato que nos dice si se trata de un usuario mentor o estudiante} param_tipo_usu
 * @param {Id de la tarea seleccionada} param_task_id
 */
function VerDatosEspecíficos(param_tipo_usu, param_task_id){
    //Rellenamos los campos:
    var id_tarea_titulo      = 'titulo_tarea_'      + param_task_id;
    var id_tarea_fecha       = 'fecha_tarea_'       + param_task_id;
    var id_tarea_descripcion = 'descripcion_tarea_' + param_task_id;

    document.getElementById('id_task'          ).textContent = param_task_id;
    document.getElementById('input_name'       ).value = document.getElementById(id_tarea_titulo     ).innerText;
    document.getElementById('input_description').value = document.getElementById(id_tarea_descripcion).innerText;

    //Formateamos la fecha para que aparezca de la manera correcta
    var fechaOriginal   = document.getElementById(id_tarea_fecha).innerText;
    var partesFecha     = fechaOriginal.split('-');
    var fechaFormateada = partesFecha[2] + '-' + partesFecha[1] + '-' + partesFecha[0];

    document.getElementById('input_last_day').value = fechaFormateada;

    if (param_tipo_usu == 1){ //Estudiante
        //Deshabilitamos los campos para que no puedan ser editados
        document.getElementById('input_name'       ).readOnly = true;
        document.getElementById('input_last_day'   ).readOnly = true;
        document.getElementById('input_description').readOnly = true;

        if (FechaEsValida(fechaFormateada)){
            document.getElementById('lbl_input_upload').style.visibility      = 'visible';
            document.getElementById('input_upload'    ).style.visibility      = 'visible';
            document.getElementById('PanelTituloE'    ).style.backgroundColor = '#0099cc';

            document.getElementById('titEmergenteShowData').innerText    = 'Añadir o modificar entrega';
            document.getElementById('guardar_tarea_estudiante').style.visibility = 'visible';
        } else {
            document.getElementById('lbl_input_upload').style.visibility      = 'hidden';
            document.getElementById('input_upload'    ).style.visibility      = 'hidden';
            document.getElementById('PanelTituloE'    ).style.backgroundColor = 'red';

            document.getElementById('titEmergenteShowData').innerText = 'Fuera de plazo';
            document.getElementById('guardar_tarea_estudiante').style.visibility = 'hidden';
        }
    }

    MostrarPanelFormulario(false);
}

/**
 * Comprueba los datos y redirecciona la funcionalidad al apartado de creación o modificación de los datos
 */
function CrearNuevaTarea(){
    var titulo      = document.getElementById('input_name'       ).value;
    var descripcion = document.getElementById('input_description').value;

    var fechaHoy  = new Date();
    var fechaForm = new Date(document.getElementById('input_last_day').value);

    //Comprobacioines
    var datosCorrectos = true;
    var texto = '';
    if (isNaN(fechaForm.getTime()) || titulo == '' || descripcion == ''){
        texto = 'Para realizar esta acción la tarea primero tiene que rellenar todos los campos';
        datosCorrectos = false;
    }else if (fechaForm <= fechaHoy){
        texto = 'La fecha introducida debe ser posterior a la fecha actual';
        datosCorrectos = false;
    }else if (titulo.length > 200) {
        texto = 'El título no puede contener más de 200 caracteres';
        datosCorrectos = false;
    }else if (descripcion.length > 4000){
        var texto = 'La descripción no puede contener más de 4.000 caracteres';
        datosCorrectos = false;
    }

    if (datosCorrectos){
        var id_task = document.getElementById('id_task').textContent;
        if(id_task == 'Texto oculto'){
            FuncionCrearNuevaTarea(titulo, descripcion, fechaForm);
        } else {
            FuncionModificarTarea(id_task, titulo, descripcion, fechaForm);
        }
    } else {
        MostrarMensajeError(texto, true);
    }
}

/**
 * Elimina la tarea seleccionada
 *
 * @param {El id de la tarea que queremos eliminarv } param_id_tarea
 * @param {El valor que tomará el campo logic_cancel} param_logic_cancel
 */
function CambiarBajaLogica(param_id_tarea, param_logic_cancel){
    var data = {
        _token : csrfToken,
        datos  : param_id_tarea,
        logic_cancel : param_logic_cancel
    }

    $.ajax({
        url   : url_delete_task,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            location.reload()
        } else {
            texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            MostrarMensajeError(param_texto, true);
        }
    });
}
//--------------------------------------------------------------------------------------------------
/**
 * Crea la petición ajax para crear la tarea nueva
 *
 * @param {Titulo de la tarea, para rellenar la base de datos tasks} param_titulo
 * @param {Descripción de la tarea, para rellenar la base de datos tasks} param_descripcion
 * @param {Fecha de la tarea, para rellenar la base de datos de tasks} param_fechaForm
 */
function FuncionCrearNuevaTarea(param_titulo, param_descripcion, param_fechaForm){
    var data = {
        _token : csrfToken,
        datos  : {
            titulo_tarea     : param_titulo,
            descripcion_tarea: param_descripcion,
            fecha_tarea      : param_fechaForm.toISOString()
        }
    }

    $.ajax({
        url: url_add_task,
        method: 'POST'   ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            location.reload()
        } else {
            texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            MostrarMensajeError(param_texto, true);
        }
    });
}
//--------------------------------------------------------------------------------------------------
/**
 *  Crea la petición ajax para modificar los datos de la tarea seleccionada
 *
 * @param {Id de la tarea que vamos a modificar, para buscarla} param_id
 * @param {Titulo de la tarea, para modificar la base de datos} param_titulo
 * @param {Descripción de la tarea, para modificar la base de datos} param_descripcion
 * @param {Fecha de entrega final de la tarea, para modificar la base de datos} param_fechaForm
 */
function FuncionModificarTarea(param_id, param_titulo, param_descripcion, param_fechaForm){
    var data = {
        _token : csrfToken,
        datos  : {
            id_tarea         : param_id         ,
            titulo_tarea     : param_titulo     ,
            descripcion_tarea: param_descripcion,
            fecha_tarea      : param_fechaForm.toISOString()
        }
    }

    $.ajax({
        url   : url_update_task,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            location.reload()
        } else {
            texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            MostrarMensajeError(param_texto, true);
        }
    });
}
//--------------------------------------------------------------------------------------------------
