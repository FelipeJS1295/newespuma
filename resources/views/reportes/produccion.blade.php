@extends('layouts.app')

@section('content')
<h2>Reporte de Producción</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Centímetros</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($producciones as $produccion)
            <tr>
                <td>{{ $produccion->producto ? $produccion->producto->nombre : 'Producto no disponible' }}</td>
                <td>{{ $produccion->cantidad }}</td>
                <td>{{ $produccion->centimetros }} cm</td>
                <td>{{ $produccion->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
