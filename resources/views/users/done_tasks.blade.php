@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/sync_chatUserStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_show_table    = "{{ route('users.show_table') }}";
        var url_send_message = "{{ route('users.send_message.store') }}";
        var url_close        = "{{ route('users.close') }}";
    </script>
    <script src="{{ asset('js/SyncChat/sync_chatScript.js') }}"></script>
    <script>
    $(document).ready(function(){
        let dataTable = new DataTable('data_table_name');
    });
    </script>
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
