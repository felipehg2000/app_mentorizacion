/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 12:25:21
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-07-08 16:37:41
 * @Description: Controlador padre de todas las vistas relacionadas con la gestión de tareas, contiene
 *               las funciones genéricas sobre estas gestiones, como mostrar paneles o realizar y descargar
 *               las tareas
 */

/**
 *
 * @param {Booleano que determina si hay que borrar los campos del panel formulario o no} param_limpiar_campos
 */
function MostrarPanelFormulario(param_limpiar_campos){
    document.getElementById('pnlOscurecer' ).style.visibility = 'visible';
    document.getElementById('PanelShowData').style.visibility = 'visible';

    if (param_limpiar_campos) {
        document.getElementById('input_name'       ).value = "";
        document.getElementById('input_last_day'   ).value = "";
        document.getElementById('input_description').value = "";
    }
}
//--------------------------------------------------------------------------------------------------
/**
 * Muestra el tablón de tareas
 */
function MostrarPanelTareas(){
    document.getElementById('id_task'         ).textContent      = 'Texto oculto';
    document.getElementById('PanelShowData'   ).style.visibility = 'hidden'      ;
    document.getElementById('pnlOscurecer'    ).style.visibility = 'hidden'      ;
    document.getElementById('PanelShowAnswers').style.visibility = 'hidden';

    if (document.getElementById('lbl_input_upload') != null){
        document.getElementById('lbl_input_upload').style.visibility = 'hidden';
        document.getElementById('input_upload'    ).style.visibility = 'hidden';
    }

    document.getElementById('guardar_tarea_estudiante').style.visibility = 'hidden';

}
//--------------------------------------------------------------------------------------------------
/**
 * Muestra el panel de entregas con las listas de los estudiantes que han realizado entregas y los que no
 *
 * @param {Identificador de la tarea seleccionada} param_task_id
 */
function MentorClickColumnDataTableTask(param_task_id) {
    var data = {
        _token : csrfToken,
        id     : param_task_id
    }

    $.ajax({
        url   : url_found_answers,
        method: 'POST'         ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            document.getElementById('pnlOscurecer').style.visibility = 'visible';

            document.getElementById('ListaEstudiantesConEntrega').innerHTML = '';
            document.getElementById('ListaEstudiantesSinEntrega').innerHTML = '';

            respuesta.usuarios_sala_estudio.forEach(function(usuario){
                var entregado = false;

                respuesta.usuarios_con_entrega.forEach(function(entrega){
                    if (entrega.id == usuario.id) {
                        entregado = true;
                        PushUser(entrega.id, entrega.NAME, entrega.SURNAME, 'ListaEstudiantesConEntrega');
                    }
                });

                if (!entregado){
                    PushUser(usuario.id, usuario.NAME, usuario.SURNAME, 'ListaEstudiantesSinEntrega');
                }
            });

            document.getElementById('id_task_answer').textContent = param_task_id;
            document.getElementById('PanelShowAnswers').style.visibility = 'visible';
        } else {
            //devolver la vista de cerrar sesión
            window.location.href = url_close;
        }
    });
}
//--------------------------------------------------------------------------------------------------
/**
 * Hacemos que ajax espere un dato de tipo Blob, lo leemos y creamos una url con ese archivo que introducimos en un enlace y lo clckeamos
 * para poder descargarlo y mostrarlo.
 *
 * @param {Identificador del usuario del que queremos descargar la entrega} pragma_usuario_id
 */
function downloadTask(pragma_usuario_id){
    var id_task = document.getElementById('id_task_answer').textContent
    var data = {
        _token : csrfToken,
        id_user: pragma_usuario_id,
        id_task: id_task
    }

    $.ajax({
        url   : url_download_file,
        method: 'POST'           ,
        data  : data,
        xhrFields: {
            responseType: 'blob' // Esperamos un tipo de dato Blob (archivo)
        }
    }).done(function(respuesta){
        // Crear un objeto Blob con la respuesta recibida
        var blob = new Blob([respuesta], {type: 'application/pdf'});
        // Crear una URL para el Blob
        var url = window.URL.createObjectURL(blob);
        // Crear un enlace <a> para descargar el archivo
        var a = document.createElement('a');
        a.href = url;
        a.download = pragma_usuario_id + '_' + id_task + '.pdf';
        document.body.appendChild(a);
        a.click();
        // Limpiar el objeto URL creado
        window.URL.revokeObjectURL(url);
    })
}
//--------------------------------------------------------------------------------------------------
/**
 * Crea enlaces a una función javascript para descargar los ficheros y pone a los enlaces el nombre y apellido de los usuarios
 * pone a cada usuario en la lista que corresponde
 *
 * @param {Identificador del usuario que vamos a mostrar                      } pragma_usuario_id
 * @param {Nombre del usuario que vamos a mostrar                             } pragma_usuario_nombre
 * @param {Apellidos del usuario que vamos a mostrar                          } pragma_usuario_ape
 * @param {Lista en la que vamos a mostrar al usuario (tareas entregadas o no)} pragma_nombre_lista
 */
function PushUser(pragma_usuario_id, pragma_usuario_nombre, pragma_usuario_ape, pragma_nombre_lista) {
    var lista = document.getElementById(pragma_nombre_lista);

    var listItem = document.createElement('li');
    var anchor   = document.createElement('a' );

    var texto = pragma_usuario_nombre + " " + pragma_usuario_ape;

    anchor.href        = 'javascript:downloadTask(' + pragma_usuario_id +')';
    anchor.textContent = texto;

    listItem.appendChild(anchor  );
    lista   .appendChild(listItem);
}
//--------------------------------------------------------------------------------------------------
/**
 * Comprueba si lo que se va a subir es un pdf, en caso de serlo llama a la función que crea una copia en el storage y en caso
 * de no ser un pdf muestra un error.
 */
function CrearNuevaRespuesta(){
    var fichero = document.getElementById('input_upload').files[0];

    if (fichero == null){
        MostrarMensajeError('Tienes que seleccionar un archivo antes de realizar la entrega', true);
    } else if (fichero.type !== 'application/pdf') {
        MostrarMensajeError('El archivo seleccionado tiene que ser de tipo PDF', true);
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
                MostrarMensajeError(texto), true;
                MostrarPanelTareas();
                if (window.location.href == url_tareas_a_completar){
                    location.reload();
                }
            } else {
                texto = 'Ha ocurrido un error, algo ha ido mal al guardar los datos';
                MostrarMensajeError(param_texto, true);
            }
        });
    }
}
//--------------------------------------------------------------------------------------------------
/**
 * Comprueba si la fecha actual es anterior o igual a la fecha límite.
 *
 * @param {Fecha límite} param_fecha
 * @returns booleano, true en caso de que sea válida, false en caso de que no
 */
function FechaEsValida(param_fecha) {
    var fechaCompleta = new Date(param_fecha);
    var fechaHoy      = new Date();

    if (fechaCompleta >= fechaHoy){
        return true;
    } else {
        return false;
    }
}
//--------------------------------------------------------------------------------------------------
