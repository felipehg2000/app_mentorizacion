@extends('layouts.plantillaUsuLogeado')

@section('title', 'Student')

@section ('style')
    <link href="{{ asset('css/friendshipStyle.css') }}" rel="stylesheet">
@endsection

@section ('main')
    <div class="pnlPrincipalFriendship">
        <h2 id='friendship_title'> {{ $titulo }}</h2>
        @foreach ($users as $user)
            <div class="pnlCard">
                <div class="pnlSuperiorCard">
                    <img src=" {{ asset('photos/users/User' . $user->USER . '.png')}}">
                </div>

                <div class="pnlInferiorCard">
                    <div class="name">{{ $user->NAME }} {{ $user->SURNAME}}</div><br>
                    <label>{{ $user->DESCRIPTION }}</label><br>
                    <form action="{{ route('students.friendship.store')}}"  method="POST">
                        @csrf
                        <input class="ocultar", id="user_user" name="user_user" value = {{$user->USER}} type="hidden">

                        @if ($errors->first('error') == $user->USER)
                            <br><small>Solicitud de amsitad enviada anteriormente.</small><br>
                        @endif
                        <div class="btnFollow">
                            <button type="submit" class="btnFollow" id="btnFollow">Unirme</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    </main>
@endsection
