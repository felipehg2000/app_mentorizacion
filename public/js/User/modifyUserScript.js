function aceptarEmergente(){
    var datos = {
        _token       : csrfToken                                     ,
        name         : document.getElementById("name"         ).value,
        surname      : document.getElementById("surname"      ).value,
        email        : document.getElementById("email"        ).value,
        user         : document.getElementById("user"         ).value,
        tipousuario  : document.getElementById("tipo_usuario" ).value,
        campoestudio : document.getElementById("campo_estudio").value,
        description  : document.getElementById("description"  ).value
    };

    if (document.getElementById("tipo_usuario").value === "1") { //Estudiante
        datos.career     = document.getElementById("career"    ).value;
        datos.first_year = document.getElementById("first_year").value;
        datos.duration   = document.getElementById("duration"  ).value;
    } else if (document.getElementById("tipo_usuario").value === "2") { //Mentor
        datos.company = document.getElementById("company").value;
        datos.job     = document.getElementById("job"    ).value;
    }

    $.ajax({
        url   : url_modify_store,
        method: 'POST',
        data  : datos
    }).done(function(respuesta){
        if (respuesta.success){
            MostrarMensajeError('Los datos han sido modificados correctamente', true);
        } else {
            MostrarMensajeError('Ha ocurrido un error y no se han podido actualizar los datos, pruebe mas tarde.', true);
        }
    });

    document.getElementById("btnEmergenteCancelar").click();
}

function abrirPnlEmergente(){
    var error_encontrado = false;

    console.log("Patataaaaa");

    if (
        document.getElementById("name" ).value.trim() === "" ||
        document.getElementById("email").value.trim() === "" ||
        document.getElementById("user" ).value.trim() === ""
       )
        {
            error_encontrado = true;
            MostrarMensajeError("Es obligatorio rellenar los campos que contengan un asterisco.");
        }

    console.log(document.getElementById("tipo_usuario").value);

    if (document.getElementById("tipo_usuario").value === "1"){
        console.log("Entra");
        var valFirstYear  = document.getElementById("first_year").value;
        var valorEntero = parseInt(valFirstYear, 10);

        if (valorEntero < 1800 || valorEntero > 2024 && !error_encontrado){
            MostrarMensajeError("El año de comienzo de la carrera debe estar entre 1800 y 2024");
            error_encontrado = true;
        }

        var valDuracion = document.getElementById("duration").value;
        valorEntero     = parseInt(valDuracion, 10);

        if (valorEntero < 1 || valorEntero > 10 && !error_encontrado) {
            MostrarMensajeError("La duración tiene que estar entre los valores 1 y 10");
            error_encontrado = true;
        }
    }

    if(!error_encontrado){
        document.getElementById("pnlOscurecer"     ).style.visibility = "visible";
        document.getElementById("pnlEmergente"     ).style.visibility = "visible";
        document.getElementById("edtPnlEmergente"  ).style.visibility = "hidden" ;

        document.getElementById("textoEmergente").innerText = "¿Está seguro de que quiere modificar los datos de esta cuenta?";
    }

}
