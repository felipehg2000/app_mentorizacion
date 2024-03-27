@extends('layouts.plantillaUsuLogeado')

@if ($tipo_usu == 1)
    @section('title', 'Estudiantes')
@elseif($tipo_usu == 2)
    @section('title', 'Mentor')
@endif

@section ('style')
    <link href="{{ asset('css/sync_chatUserStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_open_chat    = "{{ route('users.sync_chat.store') }}";
        var url_send_message = "{{ route('users.send_message.store') }}";
        var url_close        = "{{ route('users.close') }}";
    </script>
    <script src="{{ asset('js/SyncChat/sync_chatScript.js') }}"></script>
@endsection

@section ('main')
<div class='pnlChat'>
    <div class='pnlChatIzq'>
        <p class='lblTituloChats'> Chats <p>

            <div class='pnlContactosChatIzq' id='pnlContactosChatIzq'>
                @foreach ($mis_amigos as $friend)
                    <div class='friend_card' onclick="chat_selected({{ $friend->id }})" id='friend_card_hidden_{{$friend->id}}'>
                        <p class='lblFriendCard'>{{ $friend->NAME }} {{ $friend->SURNAME}}</p>
                    </div>
                @endforeach
            </div>
    </div>


    <div class='pnlChatDcha' id='pnlChatDcha'>
        <div class='pnlSuperiorChatDcha' id='pnlSuperiorChatDcha'>
            <p class='lblUsuSeleccionado' id='lblUsuSeleccionado'>PRUEBA</p>
            <p class='lblIdChatSeleccionado' id = 'lblIdChatSeleccionado' style='visibility: hidden'></p>
        </div>

        <div class='pnlMensajes' id='pnlMensajes'> <!--style="background-image: url('{{ asset('photos/sync_chat/background_photo.jpeg') }}'); background-size: cover; ">-->
        <!--Se generan los divs de los mensajes de forma dinámica en la clase sync_chatScript.js función PushMessage-->
        </div>

        <div class='pnlEscritura'> <!--style="background-image: url('{{ asset('photos/sync_chat/background_photo.jpeg') }}'); background-size: cover; ">-->
            <div class='pnlEscrituraMensajes'>
                <input class='edtMensajeCaht' id='edtMensajeChat' placeholder="Escriba aquí su mensaje" onkeydown="if(event.key === 'Enter') SendMessage()">
                <button class='btnEnviarMensaje' type="submit" onclick="SendMessage()">
                    <i class="fa fa-paper-plane" style="font-size:24px;color:blue"></i>
                </button>
            </div>
        </div>

        <div class='pnlSobreponerChatDcha' id='pnlSobreponerChatDcha'></div>
    </div>

</div>

@endsection
