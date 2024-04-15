@extends('layouts.plantillaUsuLogeado')

@section('title', 'Estudiante')

@section ('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/PlantillaTasksStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/actualFriendsScript.js') }}"></script>

    <script>
        var url_act_friends_store = "{{ route('students.actual_fruends.store') }}";
        var url_create_report_req = "{{ route('users.create_repot'           ) }}";
    </script>
@endsection
@section ('main')
    <h2> Mentor de la sala de estudio</h2><br>
    <div class="pnlPrincipalFriendship">
        @foreach ($result_users as $user)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/users/User' . $user->USER . '.png')}}">
                </div>

                <div class="pnlInferiorCard">
                    <div class="name">{{ $user->NAME }} {{ $user->SURNAME}}</div><br>
                        <label>{{ $user->DESCRIPTION }}</label><br>
                        <input class="ocultar", id="respuesta_{{$user->id}}" name="respuesta" type="hidden" value="Patata">
                        <input class="ocultar", id="user_user_{{$user->id}}" name="user_user" value = {{$user->USER}} type="hidden">
                        @if ($errors->first('error') == $user->USER)
                            <br><small>Solicitud de amsitad enviada anteriormente.</small><br>
                        @endif
                        <div class="PanelBotones">
                            <button type="submit" class="btn_create_multiple" id="btnFollow_{{$user->id}}" onclick="EliminarSeguidor({{ $user->id }})">Eliminar</button>
                            <button type="submit" class="btn_create_multiple" id="btnReport_{{$user->id}}" onclick="AbrirPanelReportes({{ $user->id }})">Reportar</button>
                        </div>
                </div>
            </div>
        @endforeach

        <div class='PanelShowAnswers' id='PanelShowData'>
            <div class='pnlEmergenteTitulo' id='PanelTituloE'>
                <!--<i class="fa fa-plus" style="font-size:24px;color:white"></i>-->
                <p class="tituloEmergente" id="titEmergenteShowData">  Reportar:</p>
                <p id='id_task' style="visibility: hidden">Texto oculto</p>
            </div>

            <div class='PanelFormNewTask' id='PanelCentrar'>
                <input type="radio" id="html" name="fav_language" value="HTML">
                <label for="html">HTML</label><br>

                <input type="radio" id="css" name="fav_language" value="CSS">
                <label for="css">CSS</label><br>

                <input type="radio" id="javascript" name="fav_language" value="JavaScript">
                <label for="javascript">JavaScript</label><br><br>

            </div>

            <div class="PanelBotones">
                <button class='btn_create_multiple' type="submit" onclick="CrearNuevoReport()">Guardar</button>
                <button class='btnEmergenteAceptarDelete' type="submit" onclick="CerrarPanelReport()">Cancelar</button>
            </div>
        </div>
    </div>

    </main>
@endsection
