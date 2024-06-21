<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:21:45
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-17 14:22:39
 * @Description: Vista asociada a la opción de menú Informes de Usuarios del tipo de usuario adminstrador
 */
-->
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
