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

    </script>
@endsection
