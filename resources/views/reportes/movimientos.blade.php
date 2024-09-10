@extends('layouts.app')

@section('content')
<h2>Reporte de Movimientos de Inventario</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Centímetros</th>
            <th>Tipo de Movimiento</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimientos as $movimiento)
            <tr>
                <td>{{ $movimiento->producto ? $produccion->producto->nombre : 'Producto no disponible' }}</td>
                <td>{{ $movimiento->cantidad }}</td>
                <td>{{ $movimiento->centimetros }} cm</td>
                <td>{{ $movimiento->tipo_movimiento }}</td>
                <td>{{ $movimiento->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
