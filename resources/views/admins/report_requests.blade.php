@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link href="{{ asset('css/done_tasksStyle.css')  }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/Admins/report_requestScript.js') }}"></script>

    <script>
        var url_bann_people = "{{ route('admin.bann_people.store') }}";
    </script>
@endsection

@section ('main')
    <div class="container">
        <div class="card">
            <div class="card-header">Informes:</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
