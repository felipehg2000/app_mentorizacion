<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:26:49
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-17 14:27:24
 * @Description: Vista asociada a las opciones de menú "Bloquear mentores", "Bloquear estudiantes" "Bloquear administradores" del tipo de usuario adminstrador
 */
-->
@extends('layouts.plantillaAdminLoegeado')

@section ('style')
    <link href="{{ asset('css/done_tasksStyle.css')  }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/Admins/block_usersScript.js') }}"></script>

    <script>
        var url_bann_people = "{{ route('admin.bann_people.store') }}";
    </script>
@endsection

@section ('main')
    <div class="container">
        <div class="card">
            <div class="card-header">Usuarios:</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts_yajra')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush



