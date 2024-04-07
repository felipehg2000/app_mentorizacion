@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/User/done_tasksScript.js') }}"></script>

@endsection

@section ('main')
<div class= "pnlInferiorEspecifico">
    <br>
    <h2 class="SubtituloApartado">Crear usuario</h2><br>

    <label for="name">Nombre:* </label><br>
    <input type="text" id="name" name="name"><br>

    <label for="surname">Apellidos: </label><br>
    <input type="text" id="surname" name="surname"><br>

    <label for="email">Email:* </label><br>
    <input type="text" id="email" name="email"><br>

    <label for="user">Usuario: </label><br>
    <input type="text" id="user" name="user"><br>

    <label for="password"></label>Contraseña:*<br>
    <input type="password" id="password" name="password"><br>

    <label for="rep_password"></label>Repetir contraseña:*<br>
    <input type="password" id="rep_password" name="rep_password"> <br>

    <label for="description">Descripción: </label><br>
    <input type="text" id="description" name="description"><br>
</div>

<div class='PanelBotones'>
        <button class='btn_create' type="submit">Crear </button><br>
</div>
@endsection
