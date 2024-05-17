<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-17 14:24:49
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-17 14:26:26
 * @Description: Vista asociada a la opción de menú "Eliminar Cuentas" del tipo de usuario adminstrador.
 *               Elimina las cuentas de otros administradores no la suya propia
 */

-->
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

