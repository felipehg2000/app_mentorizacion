
document.addEventListener('DOMContentLoaded', function() {
    var img_seleccionada = document.getElementById('tipo_img').textContent;

    document.getElementById(img_seleccionada).style.backgroundColor = 'white';
    document.getElementById(img_seleccionada).style.color           = 'blue' ;
    document.getElementById(img_seleccionada).style.font            = 'bold' ;
    document.getElementById(img_seleccionada).style.border          = '1px solid blue';
    document.getElementById(img_seleccionada).textContent           = 'Seleccionado';
})

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
