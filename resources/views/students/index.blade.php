@extends('layouts.plantillaUsuLogeado')

@section('title', 'Student')

@section ('style')
    <style type="text/css">
        body {
            background-color: rgb(184, 184, 184);
        }
        table, tr, th{
            width: 100%;
            border: 1px solid black;
        }
        table{
            border-collapse: collapse;
            background-color: white;
        }
        tr.title{
            background-color: #0099cc;
        }
        th{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
        }
        th.photo{
            width: 10%;
        }
        th.name{
            width: 20%;
        }
        th.description{
            width:30%
        }
        th.empresa{
            width: 20%;
        }
        th.puesto{
            width: 20%;
        }
    </style>
@endsection

@section ('main')
    <main>
        <table>
        <tr class="title">
            <th class="photo">FOTO</th>
            <th class="name">NOMBRE</th>
            <th class="description">DESCRIPCION</th>
            <th class="empresa">EMPRESA</th>
            <th class="puesto">PUESTO DE TRABAJO</th>
            <th class="peticion">PETICION</th>
        </tr>
            @foreach ($users as $user)
                <a href="#" class="lista_usuarios">
                    <tr class="mentors">
                        <th class="photo">FOTO</th>
                        <th class="name">{{ $user->name }} {{ $user->surname}}</th>
                        <th class="description">{{ $user->description }}</th>
                        <th class="empresa">EMPRESA</th>
                        <th class="puesto">PUESTO</th>
                        <th class="peticion"><a href="#">Unirse</a></th>
                    </tr>
                </a>
            @endforeach
        </table>
    </main>
@endsection

