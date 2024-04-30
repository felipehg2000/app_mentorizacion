var channel2;
var contenidoOriginal = '';

var pusher2 = new Pusher('7b7c6d7f8ba7188308b6', {
    cluster: 'eu'
});

document.addEventListener('DOMContentLoaded', function() {

    CrearCkEditorMentor();
    CrearCkEditorEstudiante();

    var id_channel = document.getElementById('id_tuto' ).textContent;
    var tipo_usu   = document.getElementById('tipo_usu').textContent;
    var id_usuario = document.getElementById('id_user' ).textContent;

    channel2 = pusher2.subscribe('tut_access_' + id_channel + id_usuario);

    channel2.bind('App\\Events\\TutUpdateEvent', function(data) {
        if (tipo_usu == '1') {
            //document.getElementById('textAreaMentor').value = data.data;
            editorMentor.setData(data.data)
            contenidoOriginal = data.data;
        } else if(tipo_usu == '2'){
            //document.getElementById('textAreaEstudiante').value = data.data;
            editorEstudiante.setData(data.data);
            contenidoOriginal = data.data;
        }
    });
})

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
        }, 1000); // Espera un segundo
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
            }, 1000); //Espera un segundo

        } else if(tipo_usu == '2') {
            if (editorEstudiante.getData() !== contenidoOriginal) {
                editorEstudiante.setData(contenidoOriginal);
            }
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
            document.getElementById('pnlCubierta').style.visibility = 'visible';
            document.getElementById('pnlCubiertaMensaje').style.visibility = 'visible';
            document.getElementById('pnlCubiertaMensaje').textContent = 'La tutoría ha finalizado';
        }
    });;
}
