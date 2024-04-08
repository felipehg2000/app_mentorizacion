@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/Admins/create_modifyAdminsScript.js') }}"></script>
    <script>
        var url_modify_admin_store = "{{ route('admin.modify.store') }}";
    </script>
@endsection

@section ('main')
<div class= "pnlInferiorEspecifico">
    <br>
    <h2 class="SubtituloApartado">Modifcar mis datos</h2><br>

    <label for="name">Nombre:* </label><br>
    <input type="text" id="name" name="name" value='{{ $admin['name'] }}'><br>

    <label for="surname">Apellidos: </label><br>
    <input type="text" id="surname" name="surname" value='{{ $admin['surname'] }}'><br>

    <label for="email">Email:* </label><br>
    <input type="text" id="email" name="email" value='{{ $admin['email'] }}'><br>

    <label for="user">Usuario: </label><br>
    <input type="text" id="user" name="user" value='{{ $admin['user'] }}'><br>

    <label for="password">Nueva contraseña:</label><br>
    <input type="password" id="password" name="password"><br>

    <label for="rep_password">Repetir nueva contraseña:</label><br>
    <input type="password" id="rep_password" name="rep_password"> <br>

    <label for="description">Descripción: </label><br>
    <input type="text" id="description" name="description" value='{{ $admin['description'] }}'><br>
</div>

<div class='PanelBotones'>
        <button class='btn_create' type="submit" onclick='CrearYModificarAdmins()'>Modificar</button><br>
</div>
@endsection
