@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/sync_chatUserStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script>
        var url_done_tasks    = "{{ route('users.done_tasks') }}";
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
