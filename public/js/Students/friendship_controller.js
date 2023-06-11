

function friendshipClick() {
    console.log('l');
    $.ajax({
        url: 'students.friendship_redirection',
        method: 'GET',
        success: function(response) {
            // Realizar acciones con la respuesta exitosa
            return view('student.friendship_redirection', response);
        },
        error: function(xhr, status, error) {
            // Manejar el error de la petición AJAX
            console.log('Error en la petición AJAX:', error);
        }
    });
}

