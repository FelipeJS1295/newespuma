@extends('layouts.app')

@section('content')
<h2>Reporte de Despachos</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Centímetros Despachados</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($despachos as $despacho)
            <tr>
                <td>{{ $despacho->produccion->producto->nombre }}</td>
                <td>{{ $despacho->centimetros }} cm</td>
                <td>{{ $despacho->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
