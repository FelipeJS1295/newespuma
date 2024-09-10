@extends('layouts.app')

@section('title', 'Detalles de Pagos')

@section('content')
<div class="container mt-4">
    <h2>Detalles de Pagos para Venta #{{ $venta->id }}</h2>
    
    <!-- Tabla de pagos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha de Pago</th>
                <th>Tipo de Pago</th>
                <th>Monto</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->pagos as $pago)
                <tr>
                    <td>{{ $pago->fecha_pago }}</td>
                    <td>{{ $pago->tipo_pago }}</td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->detalle }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
