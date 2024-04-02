@extends('layouts.plantillaTasks')

@section ('style2')
    <link href="{{ asset('css/done_tasksStyle.css')  }}" rel="stylesheet">
@endsection

@section('js2')
    <script src="{{ asset('js/User/done_tasksScript.js') }}"></script>

@endsection

@section ('main2')
    <p style="visibility: hidden">{{$tipo_usu = 1}}<
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
