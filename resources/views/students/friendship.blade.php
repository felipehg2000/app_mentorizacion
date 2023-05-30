@extends('layouts.plantillaUsuLogeado')

@section('title', 'Student')

@section ('style')
    <style type="text/css">
        body {
            background-color: rgb(184, 184, 184);
        }

        div.pnlPrincipalFriendship {
            display  : flex;
            flex-wrap: wrap;
        }

        div.pnlCard {
            width: 25%;

            margin : 2.5%;
            padding: 2.5%;

            background-color: rgb(184, 184, 184);

            border: 1px solid blue;
        }

        div.pnlCard img {
            width: 100%;
            height: auto;

            border      : solid;
            border-color: blue;
            border-width: 1px;

        }

        div.name {
            width: 100%;

            font-weight: bold;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            text-align : center;

            margin-top: 10px;

            border             : solid;
            border-color       : blue;
            border-bottom-width: 1px;
            border-top-width   : 0px;
            border-left-width  : 0px;
            border-right-width : 0px;
        }

        .pnlCard .details {
        margin-top: 5px;
        }

        div.btnFollow{
            display         : flex;
            justify-content : center;
            align-items     : center;
        }

        button.btnFollow{
            width   : 30%;
            padding: 10px;

            border       : none;
            border-radius: 5px;

            background-color: #0099cc;
            color           : #fff;
            font-size       : 16px;

            cursor: pointer;
            transition: background-color 0.3s ease;

            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        button.btnFollow:hover {
            background-color: #0077b3;
        }

        small{
            color: red;
            text-align:center;
        }
    </style>
@endsection

@section ('main')
    <div class="pnlPrincipalFriendship">
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
                            <button type="submit" class="btnFollow" id="btnFollow" onclick="followClick()">Unirme</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    </main>
@endsection

@section ('js')
    <script>
        function followClick(){
            console.log(1);
            document.getElementById("btnFollow").value                 = "Solicitud enviada";
            document.getElementById("btnFollow").style.Color           = "#0099cc";
            document.getElementById("btnFollow").style.backgroundColor = "white";
            document.getElementById("btnFollow").borderColor           = "blue";

        }
    </script>
@endsection
