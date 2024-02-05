@extends('layouts.plantillaUsuLogeado')

@section('title', 'Estudiantes')

@section ('style')
    <link href="{{ asset('css/sync_chatUserStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_open_chat = "{{ route('users.sync_chat.store') }}";
        var url_close     = "{{ route('users.close') }}";
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
                    {{ $friend->NAME }}
                </div>
            @endforeach
        </div>
    </div>

    <div class='pnlChatDcha'>
        <div class='pnlMensajes' id='pnlMensajes' style="background-image: url('{{ asset('photos/sync_chat/background_photo.jpeg') }}'); background-size: cover; ">
            <!--<div class='pnlMensajeUsuario'>
                <p class='mensajeUsuario'>patata patata patatapatatapatatapatatapatatapatata</p>
            </div>

            <div class='pnlMensajeContacto'>
                <p class='mensajeContacto'>patatapatatapatatapatatapatatapatatapatatapatatapatata</p>
            </div>-->
        </div>

        <div class='pnlEscritura'>
            <div class='pnlEscrituraMensajes'>
                <input class='edtMensajeCaht' id='edtMensajeChat' placeholder="Escriba aquí su mensaje">
                <button class='btnEnviarMensaje' type="submit">
                    <i class="fa fa-paper-plane" style="font-size:24px;color:blue"></i>
                </button>
            </div>
        </div>
    </div>

</div>

@endsection
