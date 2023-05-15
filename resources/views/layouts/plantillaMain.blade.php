@extends('layouts.plantillaUsuLogeado')

@section('style')
    <style type="text/css">
        main {
            width: 100%;
            height: 100%;
        }

        table {
            width: 100%;
            height: 100%;
            text-align: center;
            table-layout: fixed;
        }

        th {
            vertical-align: middle;
        }

        img.enlace {
            max-width: 85%;
            height: auto;
            border-style: solid;
            border-color: black;
        }

        img.enlace:hover {
            max-width: 92%;
            height: auto;
        }
    </style>
@endsection

@section('main')
    <main class="user_main">
        <table>
            <tr>
                <th><img class = "enlace" id = "chat"       src = " {{ asset('photos/icono_chat.png') }} "       onclick = "chatClick()">      </th>
                <th><img class = "enlace" id = "study_room" src = " {{ asset('photos/icono_study_room.png') }} " onclick = "study_roomClick()"></th>
            </tr>
            <tr>
                <th><img class = "enlace" id = "tutorials"  src = " {{ asset('photos/icono_tutorials.png') }}  " onclick = "tutorialsClick()"> </th>
                <th><img class = "enlace" id = "friendship" src = " {{ asset('photos/icono_friendship.png') }} " onclick = "friendshipClick()"></th>
            </tr>
        </table>
    </main>

    <script>
function chtClick(){

}

function study_roomClick(){

}

function tutorialsClick(){

}

function friendshipClick() {
    window.location.href = "{{ route('students.friendship_redirection') }}";
}
    </script>
@endsection
