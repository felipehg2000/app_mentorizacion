//Creación de registros
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
    }

    document.getElementById('PanelTaskBoardCreateTask').style.visibility = 'visible';
    document.getElementById('PanelTaskBoardTareas'    ).style.visibility = 'hidden' ;
}
/**
 * Muestra el panel del formulario
 */
function MostrarPanelFormulario(){
    document.getElementById('PanelTaskBoardCreateTask').style.visibility = 'visible';
    document.getElementById('PanelTaskBoardTareas'    ).style.visibility = 'hidden' ;

    document.getElementById('input_name'       ).value = "";
    document.getElementById('input_last_day'   ).value = "";
    document.getElementById('input_description').value = "";
}

/**
 * Muestra el tablón de tareas
 */
function MostrarPanelTareas(){
    document.getElementById('id_task'                 ).textContent      = 'Texto oculto';
    document.getElementById('PanelTaskBoardCreateTask').style.visibility = 'hidden' ;
    document.getElementById('PanelTaskBoardTareas'    ).style.visibility = 'visible';
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
        MostrarMensajeError(texto);
    }
}

/**
 * Elimina la tarea seleccionada
 *
 * @param {El id de la tarea que queremos eliminar} param_id_tarea
 */
function BorrarTarea(param_id_tarea){
    var data = {
        _token : csrfToken,
        datos  : param_id_tarea
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
            MostrarMensajeError(param_texto);
        }
    });
}

/**
 *
 */
function CrearNuevaRespuesta(){
    var fichero = document.getElementById('input_upload').files[0];

    if (fichero == null){
        MostrarMensajeError('Tienes que seleccionar un archivo antes de realizar la entrega');
    } else if (fichero.type !== 'application/pdf') {
        MostrarMensajeError('El archivo seleccionado tiene que ser de tipo PDF');
    } else {
        /**Como al ser un booleano no puede leer los datos antes de pasarlos porque da error, usamos
         * el tipo de dato FormData para que lea el token pero no el fichero
         */
        var formData = new FormData();
        var archivo  = document.getElementById('input_upload').files[0];
        var id_task  = document.getElementById('id_task'     ).textContent;

        formData.append('_token' , csrfToken);
        formData.append('fichero', archivo  );
        formData.append('id_task', id_task  );

        $.ajax({
            url   : url_update_file,
            method: 'POST'         ,
            data  : formData       ,
            processData : false    ,
            contentType : false
        }).done(function(respuesta){
            if(respuesta.success){
                texto = 'Tarea subida correctamente';
                MostrarMensajeError(texto);
                MostrarPanelTareas();
            } else {
                texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
                MostrarMensajeError(param_texto);
            }
        });
    }
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
            MostrarMensajeError(param_texto);
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
            MostrarMensajeError(param_texto);
        }
    });
}
//--------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------------------------
