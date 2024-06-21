
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 13:13:45
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-17 13:17:36
 * @Description: Controlador de la vista asociado a change_img_perf de todos los tipos de usuario.
 */

/**
 * Función que se ejecuta al estar todos los componentes cargados.
 * De la imagen que el usuario tnega seleccionada originalmente cambiamos el color del botón para
 * indicar que está seleccionada
 */
document.addEventListener('DOMContentLoaded', function() {
    var img_seleccionada = document.getElementById('tipo_img').textContent;

    document.getElementById(img_seleccionada).style.backgroundColor = 'white';
    document.getElementById(img_seleccionada).style.color           = 'blue' ;
    document.getElementById(img_seleccionada).style.font            = 'bold' ;
    document.getElementById(img_seleccionada).style.border          = '1px solid blue';
    document.getElementById(img_seleccionada).textContent           = 'Seleccionado';
})

/**
 * Llamamos a la función del controlador que actualiza la base de datos pasandole el index que
 * se ha seleccionado. Recargamos la página.
 *
 * @param {Integer asociado a la imagen que el usuario ha seleccionado} index
 */
function ImagenSeleccionada(index){
    var img_selecc = 'img_perf_' + index + '.JPG';

    var data = {
        _token           : csrfToken,
        img_seleccionada : img_selecc
    }

    $.ajax({
        url   : url_modify_img_store,
        method: 'POST'              ,
        data  : data
    }).done(function(respuesta){
        if(respuesta.success){
            location.reload()
        }
    });
}
