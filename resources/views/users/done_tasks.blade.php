@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/done_tasksStyle.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/done_tasksScript.js') }}"></script>
@endsection

@section ('main')
<div class="container">
    <div class="card">
        <div class="card-header">Resumen de tareas</div>
        <div class="card-body">
            {{ $dataTable->table() }}

        </div>
    </div>
</div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
