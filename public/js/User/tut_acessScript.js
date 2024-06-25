/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 13:47:57
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-05-17 13:57:27
 * @Description: Controlador de la vista tut_access.blade.php
 */

var channel2;
var contenidoOriginal = "";

var pusher2 = new Pusher('7b7c6d7f8ba7188308b6', {
    cluster: 'eu'
});

/**
 * Función que se ejecuta cuando todos los componentes están cargados.
 * Creamos los CkEditors y abrimos el canal para hacer la comunicación actualizada en tiempo real de los usuarios.
 */
document.addEventListener('DOMContentLoaded', function() {

    CrearCkEditorMentor();
    CrearCkEditorEstudiante();

    var id_channel = document.getElementById('id_tuto' ).textContent;
    var tipo_usu   = document.getElementById('tipo_usu').textContent;
    var id_usuario = document.getElementById('id_user' ).textContent;

    channel2 = pusher2.subscribe('tut_access_' + id_channel + id_usuario);

    channel2.bind('App\\Events\\TutUpdateEvent', function(data) {
        var data = {
            _token : csrfToken,
            message: data.data
        }

        $.ajax({
            url   : url_decrypt_info,
            method: 'POST'         ,
            data  : data
        }).done(function(respuesta){
            if (respuesta.success) {
                if (tipo_usu == '1') {
                    if (respuesta.message == null){
                        editorMentor.setData("");
                        contenidoOriginal = "";
                    }else {
                        editorMentor.setData(respuesta.message)
                        contenidoOriginal = respuesta.message;
                    }
                } else if(tipo_usu == '2'){
                    if (respuesta.message == null){
                        editorEstudiante.setData("");
                        contenidoOriginal = "";
                    } else {
                        editorEstudiante.setData(respuesta.message);
                        contenidoOriginal = respuesta.message;
                    }
                }
            }

        });
    });
})
//--------------------------------------------------------------------------------------------------
/**
 * Funciones para crear los CkEditors. Cambiamos los divs por el tipo de componente CkEditor y
 * añadimos los eventos que vayamso a utilizar, en este caso los siguientes:
 *      OnChange()
 *      OnKeyDown()
 *
 * También bloqueamos los iconos del CkEditor del usuario contrario al que somos.
 */
function CrearCkEditorMentor(){
    ClassicEditor
    .create( document.querySelector( '#textAreaMentor' ), {
        placeholder: 'Area de texto del mentor',

        simpleUpload: {
            uploadUrl: url_add_img,
            withCredencials: true,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept'      : 'application/json',
            }
        }

    })
    .then( editor => {
        editorMentor = editor;

        contenidoOriginal = editor.getData();

        editor.editing.view.document.on( 'change', ( evt, data ) => {
            MentorPulsaTecla();
        });

        editor.editing.view.document.on( 'keydown', ( evt, data ) => {
            if (document.getElementById('tipo_usu').textContent == '1') {
                NoGrrabarTeclaExceptoCopiar();
            }
        });

        if (document.getElementById('tipo_usu').textContent == '1'){
            for (i = 0; i < editor.ui.view.toolbar.items.length - 1; i++){
                editor.ui.view.toolbar.items.get(i).isEnabled = false;
            }
        }
    } )
    .catch( error => {
        console.error( error );
    } );
}

function CrearCkEditorEstudiante(){
    ClassicEditor
    .create( document.querySelector( '#textAreaEstudiante' ), {
        placeholder: 'Area de texto del estudiante',

        simpleUpload: {
            uploadUrl: url_add_img,
            withCredencials: true,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept'      : 'application/json',
            }
        }
    })
    .then( editor => {
        editorEstudiante = editor;

        editor.editing.view.document.on( 'change', ( evt, data ) => {
            EstudiantePulsaTecla();
        });

        editor.editing.view.document.on( 'keydown', ( evt, data ) => {
            if (document.getElementById('tipo_usu').textContent == '2') {
                NoGrrabarTeclaExceptoCopiar();
            }
        });

        if (document.getElementById('tipo_usu').textContent == '2'){
            for (i = 0; i < editor.ui.view.toolbar.items.length - 1; i++){
                editor.ui.view.toolbar.items.get(i).isEnabled = false;
            }
        }
    } )
    .catch( error => {
        console.error( error );
    } );
}
//--------------------------------------------------------------------------------------------------
/**
 * Si el usuario del tipo contrario al nuestro pulsa una tecla en nuestro CkEditor prevenimos la acción,
 * si somos nosotros quienes la pulsamos mandamos, después de cierto tiempo, la información al otro usuario
 * para hacer la actualización de la pantalla de ambos en vivo.
 */
function MentorPulsaTecla(){
    var tipo_usu = document.getElementById('tipo_usu').textContent;

    if (tipo_usu == '1') {
        if (editorMentor.getData() !== contenidoOriginal) {
            editorMentor.setData(contenidoOriginal);
        }
    } else if(tipo_usu == '2') {
        ultimaPulsacion = Date.now();

        setTimeout(function() {
            if (Date.now() - ultimaPulsacion >= 300) {
                var texto = editorMentor.getData();
                MandarTexto(texto);
            }
        }, 500); // Espera medio segundo
    }
}

function EstudiantePulsaTecla(){
        var tipo_usu = document.getElementById('tipo_usu').textContent;

        if (tipo_usu == '1') {
            ultimaPulsacion = Date.now();

            setTimeout(function() {
                if (Date.now() - ultimaPulsacion >= 300) {
                    var texto = editorEstudiante.getData();
                    MandarTexto(texto);
                }
            }, 500); //Espera medio segundo

        } else if(tipo_usu == '2') {
            if (editorEstudiante.getData() !== contenidoOriginal) {
                editorEstudiante.setData(contenidoOriginal);
            }
        }
}
//--------------------------------------------------------------------------------------------------
/**
 * Prevenimos que las teclas pulsadas, en el CkEditor, hagan su función a excepción de ctl+c
 */
function NoGrrabarTeclaExceptoCopiar(){
    var codigoTecla = event.keyCode || event.which;

    if (codigoTecla != 67 && (!event.ctrlKey || !event.metaKey)) {
        event.preventDefault();
    }
}

/**
 * Función a través de la que mandamos la información de nuestro CkEditor al usuario con el que estmos
 * teniendo la tutoría.
 *
 * @param {Texto que está en el CkEditor que corresponde al usuario logeado} param_texto
 */
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

/**
 * Solo accesible por los mentores, llama a un controlador de base de datos para actualizar el estado de las tutorías
 * y muestra la cubierta para que el mentor sepa que la tutoría ha finalizado correctamente.
 */
function FinalizarTutoria(){
    var id_tuto = document.getElementById('id_tuto').textContent;

    var data = {
        _token : csrfToken,
        id_tuto: id_tuto
    }

    $.ajax({
        url   : url_fin_tuto,
        method: 'POST'      ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            document.getElementById('pnlCubierta'       ).style.visibility = 'visible';
            document.getElementById('pnlCubiertaMensaje').style.visibility = 'visible';
            document.getElementById('pnlCubiertaMensaje').textContent = 'La tutoría ha finalizado';
        }
    });;
}
