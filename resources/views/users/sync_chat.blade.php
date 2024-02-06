@extends('layouts.plantillaUsuLogeado')

@section('title', 'Estudiantes')

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
        <div class='pnlBusquedaChatIzq'>
            <i class='fa fa-search' style="font-size:20px;color:black"></i>
            <input class='edtBusquedaChat' id='edtBusquedaChat' placeholder="Busque un chat">
        </div>
        <div class='pnlContactosChatIzq'>
            @foreach ($mis_amigos as $friend)
                <div class='friend_card' onclick="chat_selected({{ $friend->id }})">
                    {{ $friend->NAME }} {{ $friend->id }}
                </div>
            @endforeach
        </div>
    </div>

    <div class='pnlChatDcha'>
        <div class='pnlMensajes' id='pnlMensajes' style="background-image: url('{{ asset('photos/sync_chat/background_photo.jpeg') }}'); background-size: cover; ">
        <!--Se generan los divs de los mensajes de forma dinámica en la clase sync_chatScript.js función PushMessage-->
        </div>

        <!--
            PANEL CON EL EDIT PARA MANDAR EL MENSAJE
        -->
        <div class='pnlEscritura'>
            <div class='pnlEscrituraMensajes'>
                <input class='edtMensajeCaht' id='edtMensajeChat' placeholder="Escriba aquí su mensaje" onkeydown="if(event.key === 'Enter') SendMessage()">
                <button class='btnEnviarMensaje' type="submit" onclick="SendMessage()">
                    <i class="fa fa-paper-plane" style="font-size:24px;color:blue"></i>
                </button>
            </div>
        </div>
    </div>

</div>

@endsection
