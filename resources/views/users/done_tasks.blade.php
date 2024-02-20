@extends('layouts.plantillaUsuLogeado')

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
<div class="container">
    <div class="card">
        <div class="card-header">Tareas finalizadas</div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
