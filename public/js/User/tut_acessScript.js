function ActivarPizarraClick(){
    if (document.getElementById('pnlPizarra').style.visibility == 'visible'){
        document.getElementById('pnlPizarra').style.visibility = 'hidden';
        document.getElementById('btnPizarra').textContent = 'Activar Pizarra';
    } else {
        document.getElementById('pnlPizarra').style.visibility = 'visible';
        document.getElementById('btnPizarra').textContent = 'Desactivar Pizarra';
    }
}

function MentorPulsaTecla(){
    var tipo_usu = document.getElementById('tipo_usu').textContent;

    if (tipo_usu == '1') {
        NoGrrabarTeclaExceptoCopiar();
    } else if(tipo_usu == '2') {

    }
}

function EstudiantePulsaTecla(){
        var tipo_usu = document.getElementById('tipo_usu').textContent;

        if (tipo_usu == '1') {

        } else if(tipo_usu == '2') {
            NoGrrabarTeclaExceptoCopiar();
        }
}

function NoGrrabarTeclaExceptoCopiar(){
    var codigoTecla = event.keyCode || event.which;

    if (codigoTecla != 67 && (!event.ctrlKey || !event.metaKey)) {
        event.preventDefault();
    }
}
