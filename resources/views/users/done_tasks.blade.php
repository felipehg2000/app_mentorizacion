<!--
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-16 12:43:18
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-16 12:44:30
 * @Description: Vista relacionada con las opciones de menú tareas completadas y tareas a completar
 *               mostramos la tabla de yarja.
 */

-->
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
