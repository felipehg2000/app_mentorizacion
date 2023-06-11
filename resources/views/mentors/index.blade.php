<!--Heredamos de la plantillaMain el aspecto visual-->
@extends('layouts.plantillaMain')

<!--Modificamos el titulo de la pantalla-->
@section('title', 'Mentor')

<!--Creamos las funciones específicas js para los distintos tipos de usuario-->
@section('script')
    <script>
/**
 * Función para redirigir la imagen que representa a la parte de amistad de los mentores
*/
function friendshipClick() {
    window.location.href = "{{ route('mentors.friendship') }}";
}
/**
 * Función para redirigir la imagen que representa a la parte de amistad de los mentores
*/
function chtClick(){

}
/**
 * Función para redirigir la imagen que representa a la parte de sala de estudio de los mentores
*/
function study_roomClick(){

}
/**
 * Función para redirigir la imagen que representa a la parte de tutorías de los mentores
*/
function tutorialsClick(){

}
/**
 * Función que controla todos los distintos apartados del menú
*/
function redirection(index) {
        switch(index){
            case 1:
                window.location.href = "#";
                break;
            case 2:
                window.location.href = "#";
                break;
            case 3:
                window.location.href = "#";
                break;
            case 4:
                window.location.href = "#";
                break;
            case 5:
                window.location.href = "#";
                break;
            case 6:
                window.location.href = "#";
                break;
            case 7:
                window.location.href = "#";
                break;
            case 8:
                window.location.href = "{{route('mentors.actual_fruends')}}";
                break;
            case 9:
                window.location.href = "{{route('mentors.friendship')}}";
                break;
            case 10:
                window.location.href = "{{route('users.modify')}}";
                break;
            case 11:
                window.location.href = "{{route('users.delete')}}";
                break;
            case 12:
                window.location.href = "{{route('users.close')}}";
                break;
        }
    }
    </script>
@endsection
