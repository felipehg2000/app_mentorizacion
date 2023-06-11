@extends('layouts.plantillaUsuLogeado')

@section('title', 'Estudiantes')

@section ('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
@endsection

@section ('main')
    <div class="pnlPrincipalFriendship">
        @foreach ($result_users as $user)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/users/User' . $user->USER . '.png')}}">
                </div>

                <div class="pnlInferiorCard">
                    <div class="name">{{ $user->NAME }} {{ $user->SURNAME}}</div><br>
                    <label>{{ $user->DESCRIPTION }}</label><br>
                    <form action="{{ route('students.actual_fruends.store')}}"  method="POST">
                        @csrf
                        <input class="ocultar", id="respuesta" name="respuesta" type="hidden" value="Patata">
                        <input class="ocultar", id="user_user" name="user_user" value = {{$user->USER}} type="hidden">
                        @if ($errors->first('error') == $user->USER)
                            <br><small>Solicitud de amsitad enviada anteriormente.</small><br>
                        @endif
                        <div class="btnFollow">
                            <button type="submit" class="btnFollow" id="btnFollow">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    </main>
@endsection