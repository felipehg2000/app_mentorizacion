@extends('layouts.plantillaUsuLogeado')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/formsSimpleStyle.css') }}">
@endsection
@section('title', 'Modificar datos')

@section('js')
    <script>
        var url_modify_store = "{{route('users.modify_password.store')}}";
    </script>
    <script src="{{ asset('js/User/modify_password.js') }}"></script>
@endsection

@section ('main')
<div class= "pnlInferiorEspecifico">
    <br>
    <h2 class="SubtituloApartado">Modifcar mis datos</h2><br>

    <label for="actual_password">Contraseña actual: *</label><br>
    <input type="password" id="actual_password" name="actual_password">
    <a onmousedown='MouseDownActual()' onmouseup='MouseUpActual()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br>

    <label for="password">Nueva contraseña: *</label><br>
    <input type="password" id="password" name="password">
    <a onmousedown='MouseDownNew()' onmouseup='MouseUpNew()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br>

    <label for="rep_password">Repetir nueva contraseña: *</label><br>
    <input type="password" id="rep_password" name="rep_password">
    <a onmousedown='MouseDownRep()' onmouseup='MouseUpRep()'><i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i></a><br>
</div>
<div class='PanelBotones'>
    <button class='btn_create' type="submit" onclick='ModifyPassword()'>Modificar</button><br>
</div>
@endsection
