@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link href="{{ asset('css/done_tasksStyle.css')  }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/Admins/deleteAdminsScript.js') }}"></script>

    <script>
        var url_delete_admin = "{{ route('admin.delete.store') }}";
    </script>

@endsection

@section ('main')
    <div class="container">
        <div class="card">
            <div class="card-header">Borrar administradores:</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

