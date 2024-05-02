@extends('layouts.plantillaUsuLogeado')

@section('title', 'Mentor')

@section ('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/formsSimpleStyle.css')}}" rel="stylesheet">
@endsection

@section ('main')
    <h2 id='friendship_title'> {{ $titulo }}</h2><br>
    <div class="pnlPrincipalFriendship">
        @foreach ($result_user as $user)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/Perfiles/' . $user->IMAGE)}}">
                </div>

                <div class="pnlInferiorCard">
                    <div class="name">{{ $user->NAME }} {{ $user->SURNAME}}</div><br>
                    <label>{{ $user->DESCRIPTION }}</label><br>
                    <form action="{{ route('mentors.friendship.store')}}"  method="POST">
                        @csrf
                        <input class="ocultar", id="respuesta_{{$user->id}}" name="respuesta" type="hidden" value="Patata">
                        <input class="ocultar", id="user_user_{{$user->id}}" name="user_user" value = {{$user->USER}} type="hidden">
                        @if ($errors->first('error') == $user->USER)
                            <br><small>Solicitud de amsitad enviada anteriormente.</small><br>
                        @endif
                        <!--<div class="btnFollow">
                            <button type="submit" class="btnFollow" id="btnFollow_{{$user->id}}" onmouseover="cambiarInputOculto('ACEPTAR')">Aceptar</button>
                            <button type="submit" class="btnFollow" id="btnFollow_{{$user->id}}" onmouseover="cambiarInputOculto('DENEGAR')">Denegar</button>

                        </div>-->

                        <div class="PanelBotones">
                            <button type="submit" class="btn_create_multiple" id="btnFollow_{{$user->id}}"  onmouseover="cambiarInputOculto('ACEPTAR', {{ $user->id }})">Aceptar</button>
                            <button type="submit" class="btn_create_multiple" id="btnDenegar_{{$user->id}}" onmouseover="cambiarInputOculto('DENEGAR', {{ $user->id }})">Denegar</button>

                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    </main>

    <script>
        function cambiarInputOculto(variable, user_id){
            console.log(1);
            document.getElementById("respuesta_" + user_id).value = variable;
        }
    </script>
@endsection
