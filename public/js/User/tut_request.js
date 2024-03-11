$(document).ready(function(){
    let dataTable = new DataTable('tutoring_table');
});

function MostrarNewTutoring(param_tipo_usuario, param_tutoring_id){
    if (param_tipo_usuario == 1) {
        MostrarPanel(false, true);
    } else if (param_tipo_usuario == 2) {
        LimpiarPnlNewTutoring()
        MostrarPanel(true, false);
    }
}

function LimpiarPnlNewTutoring(){
    document.getElementById('input_fecha' ).innerText = '';
    document.getElementById('input_hora'  ).innerText = '';
    document.getElementById('input_estado').values    = '0';
}

function MostrarPanel(param_readOnly_fecha_hora, param_readOnly_estado){
    document.getElementById('pnlOscurecer').style.visibility = 'visible';

    document.getElementById('input_fecha' ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_hora'  ).readOnly = param_readOnly_fecha_hora;
    document.getElementById('input_estado').disabled = param_readOnly_estado    ;

    document.getElementById('new_tutoring').style.visibility = 'visible';
}

function MostrarTabla(){
    document.getElementById('pnlOscurecer').style.visibility = 'hidden';
    document.getElementById('new_tutoring').style.visibility = 'hidden';
}

function ClickDataTable(param_id_tut){
    MostrarPanel(false, true);
}
