@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/sync_chatUserStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
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
