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
            document.getElementById('pnlRespuestaEmergente'  ).style.visibility = "visible";
            document.getElementById('textoEmergenteRespuesta').innerText        = "Los datos han sido modificados correctamente";
        } else {
            document.getElementById('pnlRespuestaEmergente'  ).style.visibility = "visible";
            document.getElementById('textoEmergenteRespuesta').innerText        = "Ha ocurrido un error y no se han podido actualizar los datos, pruebe mas tarde por favor";
        }
    });

    document.getElementById("btnEmergenteCancelar").click();
}

function abrirPnlEmergente(){
    var mostrar_panel = true;

    if (
        document.getElementById("name" ).value.trim() === "" ||
        document.getElementById("email").value.trim() === "" ||
        document.getElementById("user" ).value.trim() === ""
       )
        {
            alert("Es obligatorio rellenar los camppos que contengan un asterisco");
            mostrar_panel = false;
        }

    console.log(document.getElementById("tipo_usuario").value);
    if (document.getElementById("tipo_usuario").value === "1"){
        var valFirstYear  = document.getElementById("first_year").value;
        var valorEntero = parseInt(valFirstYear, 10);

        if (valorEntero < 1800 || valorEntero > 2024){
            alert("El año de comienzo de la carrera debe estar entre 1800 y 2024");
            mostrar_panel = false;
        }

        var valDuracion = document.getElementById("duration").value;
        valorEntero     = parseInt(valDuracion, 10);

        if (valorEntero < 1 || valorEntero > 10) {
            alert("La duración tiene que estar entre los valores 1 y 10");
        }
    }

    if (mostrar_panel){
        document.getElementById("pnlOscurecer"  ).style.visibility = "visible";
        document.getElementById("pnlEmergente"  ).style.visibility = "visible";
        document.getElementById("textoEmergente").innerText = "¿Está seguro de que quiere modificar los datos de su usuario?";
    }
}
