@extends('layouts.plantillaUsuLogeado')

@section ('style')
    <link href="{{ asset('css/tut_request.css'    )  }}" rel="stylesheet">
    <link href="{{ asset('css/done_tasksStyle.css')  }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/User/tut_request.js') }}"></script>
    <script>
        var url_close = "{{ route('users.close') }}";
    </script>
@endsection

@section ('main')
<div class="container">
    <div class="card">
        <div class="card-header"> Solicitudes de tutorías</div>
        <div class="card-body">
            {{ $dataTable->table() }}

        </div>
    </div>
</div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
