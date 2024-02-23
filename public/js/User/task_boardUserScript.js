//Creación de registros
//--------------------------------------------------------------------------------------------------
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
    document.getElementById('PanelTaskBoardCreateTask').style.visibility = 'hidden' ;
    document.getElementById('PanelTaskBoardTareas'    ).style.visibility = 'visible';
}

/**
 * Comprueba si los datos cumplen los requisitos de la base de datos y se mandan al controlador
 * para crear el registro de la base de datos
 */
function CrearNuevaTarea(){
    console.log('1');
    var titulo      = document.getElementById('input_name'       ).value;
    var descripcion = document.getElementById('input_description').value;

    var fechaHoy  = new Date();
    var fechaForm = new Date(document.getElementById('input_last_day').value);

    //Comprobacioines
    var datosCorrectos = true;
    var texto = '';
    if (fechaForm == null || titulo == null || descripcion == null){
        texto = 'Para dar de alta la tarea primero tiene que rellenar todos los campos';
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
        var data = {
            _token : csrfToken,
            datos  : {
                titulo_tarea     : titulo,
                descripcion_tarea: descripcion,
                fecha_tarea      : fechaForm
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
                datosCorrectos = false;
                texto          = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
            }
        });
    }

    if(!datosCorrectos){
        document.getElementById('textoEmergenteRespuesta').value = texto;

        document.getElementById('pnlOscurecer'           ).style.visibility = 'visible';
        document.getElementById('pnlRespuestaEmergente'  ).style.visibility = 'visible';
    }
}
//--------------------------------------------------------------------------------------------------
