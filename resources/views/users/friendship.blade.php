@extends('layouts.plantillaUsuLogeado')

@if ($user_type == 1)
    @section('title', 'Estudiante')
@elseif ($user_type == 2)
    @section('title', 'Mentor')
@endif

@section ('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
@endsection

@section ('js')
    <script>
        function cambiarInputOculto(variable, user_id){
            console.log(1);
            document.getElementById("respuesta_" + user_id).value = variable;
        }
    </script>
@endsection

@section ('main')
    <h2 id='friendship_title'> {{ $titulo }}</h2>
    <div class="pnlPrincipalFriendship">
        @foreach ($users as $user)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/Perfiles/' . $user->IMAGE)}}">
                </div>

                <div class="pnlInferiorCard">
                    <div class="name">{{ $user->NAME }} {{ $user->SURNAME}}</div><br>

                    @if ($user_type == 1)
                        <form action="{{ route('users.friendship.store')}}"  method="POST">
                            @csrf
                            <input class="ocultar", id="user_user_{{$user->id}}" name="user_user" value = {{$user->USER}} type="hidden">

                            @if ($errors->first('error') == $user->USER)
                                <br><small>Solicitud de amsitad enviada anteriormente.</small><br>
                            @endif

                            <div class="btnFollow">
                                <button type="submit" class="btnFollow" id="btnFollow_{{$user->id}}">Unirme</button>
                            </div>
                        </form>

                    @elseif ($user_type == 2)
                        <form action="{{ route('users.friendship.store')}}"  method="POST">
                            @csrf
                            <input class="ocultar", id="respuesta_{{$user->id}}" name="respuesta" type="hidden" value="Patata">
                            <input class="ocultar", id="user_user_{{$user->id}}" name="user_user" value = {{$user->USER}} type="hidden">


                            <div class="PanelBotones">
                                <button type="submit" class="btn_create_multiple" id="btnFollow_{{$user->id}}"  onmouseover="cambiarInputOculto('ACEPTAR', {{ $user->id }})">Aceptar</button>
                                <button type="submit" class="btn_create_multiple" id="btnDenegar_{{$user->id}}" onmouseover="cambiarInputOculto('DENEGAR', {{ $user->id }})">Denegar</button>

                            </div>
                        </form>
                    @endif

                </div>
            </div>
        @endforeach
    </div>

    </main>
@endsection
