/**
 * brief         Dar textos a los label al poner el foco sobre el input al que pertenecen
 * description   Dar textos a los label al poner el foco sobre el input al que pertenecen
 * arguments     Índice del label que tenemos que modificar
 * return        Nada
 */
function createLabel(index){
    switch(index){
        case 1:
            document.getElementById("lbl_name")         .innerHTML = "Nombre";
            break;
        case 2:
            document.getElementById("lbl_surname")      .innerHTML = "Apellidos";
            break;
        case 3:
            document.getElementById("lbl_email")        .innerHTML = "Email";
            break;
        case 4:
            document.getElementById("lbl_password")     .innerHTML = "Contraseña";
            break;
        case 5:
            document.getElementById("lbl_rep_password") .innerHTML = "Repetir contraseña";
            break;
        case 6:
            document.getElementById("lbl_user_type")    .innerHTML = "Tipo de usuario";
            break;
        case 7:
            document.getElementById("lbl_career")       .innerHTML = "Estudios que cursa";
            break;
        case 8:
            document.getElementById("lbl_first_year")   .innerHTML = "Primer año";
            break;
        case 9:
            document.getElementById("lbl_duration")     .innerHTML = "Duración";
            break;
        case 10:
            document.getElementById('lbl_company')      .innerHTML = "Empresa";
            break;
        case 11:
            document.getElementById('lbl_job')          .innerHTML = "Puesto de trabajo";
            break;
        case 12:
            document.getElementById('lbl_study_area')   .innerHTML = "Area de estudio";
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
            document.getElementById("lbl_name")         .innerHTML = "";
            break;
        case 2:
            document.getElementById("lbl_surname")      .innerHTML = "";
            break;
        case 3:
            document.getElementById("lbl_email")        .innerHTML = "";
            break;
        case 4:
            document.getElementById("lbl_password")     .innerHTML = "";
            break;
        case 5:
            document.getElementById("lbl_rep_password") .innerHTML = "";
            break;
        case 6:
            document.getElementById("lbl_user_type")    .innerHTML = "";
            break;
        case 7:
            document.getElementById("lbl_career")       .innerHTML = "";
            break;
        case 8:
            document.getElementById("lbl_first_year")   .innerHTML = "";
            break;
        case 9:
            document.getElementById("lbl_duration")     .innerHTML = "";
            break;
        case 10:
            document.getElementById('lbl_company')      .innerHTML = "";
            break;
        case 11:
            document.getElementById('lbl_job')          .innerHTML = "";
            break;
        case 12:
            document.getElementById('lbl_study_area')   .innerHTML = "";
            break;

    }
}

/**
 * brief         Crear de forma dinámica los label e input específicos del estudiante o del mentor
 * description   Crear de forma dinámica los label e input específicos del estudiante o del mentor
 * arguments     Índice de si tenemos que agregar los componentes del estudiante o del mentor
 * return        Nada
 */
function userTypeChange(value){
    if (value == '0'){
        borrarElementosEstudiante();
        borrarElementosMentor();
    }else if (value == '1'){
        borrarElementosEstudiante();
        borrarElementosMentor();

        var pnlLeft = document.getElementById("pnlLeft");

        // Crea los elementos <label>
        var label_career     = document.createElement('label');
        var label_first_year = document.createElement('label');
        var label_duration   = document.createElement('label');

        // Crear los elementos <input>
        var input_career     = document.createElement('input');
        var input_first_year = document.createElement('input');
        var input_duration   = document.createElement('input');

        // Configura los contenidos de los <label>
        label_career.htmlFor       = 'input_career';
        label_career.id            = 'lbl_career';
        label_career.className     = 'left';
        label_career.textContent   = '';

        label_first_year.htmlFor   = 'input_first_year';
        label_first_year.id        = 'lbl_first_year';
        label_first_year.className = 'left';
        label_first_year.textContent = '';

        label_duration.htmlFor     = 'input_duration';
        label_duration.id          = 'lbl_duration';
        label_duration.className   = 'left';
        label_duration.textContent = '';

        input_career.className       = 'left';
        input_career.type            = 'text';
        input_career.id              = 'input_career';
        input_career.placeholder     = 'Estudios que cursa';
        input_career.onfocus         = createLabel.bind(null, 7);
        input_career.onblur          = deleteLabel.bind(null, 7);

        input_first_year.className   = 'left';
        input_first_year.type        = 'number';
        input_first_year.id          = 'input_first_year';
        input_first_year.placeholder = 'Primer año';
        input_first_year.onfocus     = createLabel.bind(null, 8);
        input_first_year.onblur      = deleteLabel.bind(null, 8);

        input_duration.className     = 'left';
        input_duration.type          = 'number';
        input_duration.id            = 'input_duration';
        input_duration.placeholder   = 'Duración';
        input_duration.onfocus       = createLabel.bind(null, 9);
        input_duration.onblur        = deleteLabel.bind(null, 9);

        // Agrega los elementos al contenedor
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(label_career);
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(input_career);
        pnlLeft.appendChild(document.createElement('br'));

        pnlLeft.appendChild(label_first_year);
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(input_first_year);
        pnlLeft.appendChild(document.createElement('br'));

        pnlLeft.appendChild(label_duration);
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(input_duration);
        pnlLeft.appendChild(document.createElement('br'));

    }else if (value == '2'){
        borrarElementosEstudiante();
        borrarElementosMentor();

        var pnlLeft = document.getElementById("pnlLeft");

        // Crea los elementos <label>
        var label_company = document.createElement('label');
        var label_job     = document.createElement('label');

        // Crear los elementos <input>
        var input_company = document.createElement('input');
        var input_job     = document.createElement('input');

        // Configura los contenidos de los <label>
        label_company.htmlFor       = 'input_company';
        label_company.id            = 'lbl_company';
        label_company.className     = 'left';
        label_company.textContent   = '';

        label_job.htmlFor       = 'input_job';
        label_job.id            = 'lbl_job';
        label_job.className     = 'left';
        label_job.textContent   = '';

        input_company.className   = 'left';
        input_company.type        = 'text';
        input_company.id          = 'input_company';
        input_company.placeholder = 'Empresa';
        input_company.onfocus     = createLabel.bind(null, 10);
        input_company.onblur      = deleteLabel.bind(null, 10);

        input_job.className     = 'left';
        input_job.type          = 'text';
        input_job.id            = 'input_job';
        input_job.placeholder   = 'Puesto de trabajo';
        input_job.onfocus       = createLabel.bind(null, 11);
        input_job.onblur        = deleteLabel.bind(null, 11);

        // Agrega los elementos al contenedor
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(label_company);
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(input_company);
        pnlLeft.appendChild(document.createElement('br'));

        pnlLeft.appendChild(label_job);
        pnlLeft.appendChild(document.createElement('br'));
        pnlLeft.appendChild(input_job);
        pnlLeft.appendChild(document.createElement('br'));
    }
}

/**
 * brief         Borrar los componentes creados dinámicamente para el estudiante
 * description   Borrar los componentes creados dinámicamente para el estudiante
 * arguments     Ninguno
 * return        Nada
 */
function borrarElementosEstudiante() {
    var pnlLeft = document.getElementById("pnlLeft");

    // Buscar y eliminar los elementos específicos por su id
    var lblCareer = document.getElementById("lbl_career");
    var lblFirstYear = document.getElementById("lbl_first_year");
    var lblDuration = document.getElementById("lbl_duration");
    var inputCareer = document.getElementById("input_career");
    var inputFirstYear = document.getElementById("input_first_year");
    var inputDuration = document.getElementById("input_duration");

    if (lblCareer) {
      pnlLeft.removeChild(lblCareer);
    }
    if (lblFirstYear) {
      pnlLeft.removeChild(lblFirstYear);
    }
    if (lblDuration) {
      pnlLeft.removeChild(lblDuration);
    }
    if (inputCareer) {
      pnlLeft.removeChild(inputCareer);
    }
    if (inputFirstYear) {
      pnlLeft.removeChild(inputFirstYear);
    }
    if (inputDuration) {
      pnlLeft.removeChild(inputDuration);
    }

    // Buscar y eliminar los elementos <br> adicionales
    var brElements = pnlLeft.getElementsByTagName("br");
    for (var i = brElements.length - 1; i >= 0; i--) {
      pnlLeft.removeChild(brElements[i]);
    }
}

/**
 * brief         Borrar los componentes creados dinámicamente para el mentor
 * description   Borrar los componentes creados dinámicamente para el mentor
 * arguments     Ninguno
 * return        Nada
 */
function borrarElementosMentor() {
  var pnlLeft = document.getElementById("pnlLeft");

  // Buscar y eliminar los elementos específicos por su id
  var lblCompany = document.getElementById("lbl_company");
  var lblJob = document.getElementById("lbl_job");
  var inputCompany = document.getElementById("input_company");
  var inputJob = document.getElementById("input_job");

  if (lblCompany) {
    pnlLeft.removeChild(lblCompany);
  }
  if (lblJob) {
    pnlLeft.removeChild(lblJob);
  }
  if (inputCompany) {
    pnlLeft.removeChild(inputCompany);
  }
  if (inputJob) {
    pnlLeft.removeChild(inputJob);
  }

  // Buscar y eliminar los elementos <br> adicionales
  var brElements = pnlLeft.getElementsByTagName("br");
  for (var i = brElements.length - 1; i >= 0; i--) {
    pnlLeft.removeChild(brElements[i]);
  }
}
