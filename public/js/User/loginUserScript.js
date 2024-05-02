/**
 * brief         Dar textos a los label al poner el foco sobre el input al que pertenecen
 * description   Dar textos a los label al poner el foco sobre el input al que pertenecen
 * arguments     Índice del label que tenemos que modificar
 * return        Nada
 */
function createLabel(index){
    switch(index){
        case 1:
            document.getElementById("lbl_user")     .innerHTML = "Usuario";
            break;
        case 2:
            document.getElementById("lbl_password") .innerHTML = "Contraseña";
            break;
    }
}

/**
 * brief         Quitar el textos a los label al poner el foco sobre el input al que pertenecen
 * description   Quitar el textos a los label al poner el foco sobre el input al que pertenecen
 * arguments     Índice del label que tenemos que modificar
 * return        Nada
 */
function deleteLabel(index){
    switch(index){
        case 1:
            document.getElementById("lbl_user")     .innerHTML = "";
            break;
        case 2:
            document.getElementById("lbl_password") .innerHTML = "";
            break;
    }
}

function MouseDownPassword(){
    document.getElementById('password').type = 'text';
}

function MouseUpPassword(){
    document.getElementById('password').type = 'password';
}
