/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 13:20:26
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-06-30 12:25:56
 * @Description: Controlador de las vistas create y modify de los administradores.
 */
//--------------------------------------------------------------------------------------------------
/**
 * Hace las comprobaciones de la información muestra los mensajes de error y en caso de que todo esté correcto
 * llama al controlador para que lo guarde en la base de datos y gestiona la respuesta.
 */
function CrearYModificarAdmins() {
    var nombre       = document.getElementById('name'        ).value;
    var apellidos    = document.getElementById('surname'     ).value;
    var email        = document.getElementById('email'       ).value;
    var user         = document.getElementById('user'        ).value;
    var password     = document.getElementById('password'    ).value;
    var rep_password = document.getElementById('rep_password').value;
    var description  = document.getElementById('description' ).value;

    var insertar_datos = true;
    var mensaje = "";

    if (window.location.href == url_create_admin){
        if (nombre == "" || email == "" || password == "" || rep_password == "" || user == ""){
            insertar_datos = false;
            mensaje = "ERROR: todos los campos con * deben estar rellenos";
        }
    } else if (window.location.href == url_modificar_mis_datos) {
        if (nombre == "" || email == "" || user == ""){
            insertar_datos = false;
            mensaje = "ERROR: todos los campos con * deben estar rellenos";
        }
    } else if (password != rep_password) {
        insertar_datos = false
        mensaje = "ERROR: los campos de contraseñas deben tener el mismo texto";
    }

    if (!insertar_datos){
        MostrarMensajeError(mensaje, true);
    } else {
        var data = {
            _token      : csrfToken,
            nombre      : nombre   ,
            apellidos   : apellidos,
            email       : email    ,
            user        : user     ,
            password    : password ,
            description : description
        }

        var url_llamada = "";
        var mensaje_bien = ";"
        if (window.location.href == url_create_admin){
            url_llamada = url_create_admin_store;
            mensaje_bien = "Administrador creado correctamente";
        } else if (window.location.href == url_modificar_mis_datos){
            url_llamada = url_modify_admin_store;
            mensaje_bien = "Datos actualizados correctamente";
        }

        $.ajax({
            url   : url_llamada,
            method: 'POST'     ,
            data  : data
        }).done(function(respuesta){
            if(respuesta.success){
                MostrarMensajeError(mensaje_bien, true)
                if (window.location.href == url_create_admin){
                    document.getElementById('name'        ).value = "";
                    document.getElementById('surname'     ).value = "";
                    document.getElementById('email'       ).value = "";
                    document.getElementById('user'        ).value = "";
                    document.getElementById('password'    ).value = "";
                    document.getElementById('rep_password').value = "";
                    document.getElementById('description' ).value = "";
                }
            } else {
                if (!respuesta.validate){
                    texto = 'El email o usuario ya está registrado, modifiquelo'
                }else{
                    texto = 'No se han encontrado los datos a modificar';
                }
                MostrarMensajeError(texto, true);
            }
        });
    }
}
//--------------------------------------------------------------------------------------------------
/**
 * Funciones para mostrar y ocultar la contraseña cuando el botón de visualización esté pulsado o no
 */
function MouseDownPassword(){
    document.getElementById('password').type = 'text';
}

function MouseUpPassword(){
    document.getElementById('password').type = 'password';
}

function MouseDownRep(){
    document.getElementById('rep_password').type = 'text';
}

function MouseUpRep(){
    document.getElementById('rep_password').type = 'password';
}
//--------------------------------------------------------------------------------------------------
