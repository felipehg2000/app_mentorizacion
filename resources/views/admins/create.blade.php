@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}">
@endsection

@section('js')
<script src="{{ asset('js/Admins/create_modifyAdminsScript.js') }}"></script>
    <script>
        var url_create_admin_store = "{{ route('admin.create.store') }}";
    </script>
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
    <input type="password" id="password" name="password">
    <a onmousedown='MouseDownPassword()' onmouseup='MouseUpPassword()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br>


    <label for="rep_password"></label>Repetir contraseña:*<br>
    <input type="password" id="rep_password" name="rep_password">
    <a onmousedown='MouseDownRep()' onmouseup='MouseUpRep()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br>


    <label for="description">Descripción: </label><br>
    <input type="text" id="description" name="description"><br>
</div>

<div class='PanelBotones'>
        <button class='btn_create' type="submit" onclick='CrearYModificarAdmins()'>Crear </button><br>
</div>
@endsection
