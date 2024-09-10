@extends('layouts.app')

@section('content')
<h2>Reporte de Ventas</h2>

<!-- Formulario de filtros -->
<form action="{{ route('reporte.ventas') }}" method="GET">
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="fecha_desde">Fecha Desde</label>
            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
        </div>
        <div class="col-md-3">
            <label for="fecha_hasta">Fecha Hasta</label>
            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
        </div>
        <div class="col-md-3">
            <label for="cliente">Cliente</label>
            <select name="cliente" id="cliente" class="form-control">
                <option value="">Todos los Clientes</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ request('cliente') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
        </div>
    </div>
</form>

<!-- Tabla de ventas -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->subtotal, 0) }}</td>
                <td>{{ $venta->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<!-- Mostrar la suma total de todas las ventas -->
<div class="mt-4">
    <h4>Total de Ventas: ${{ number_format($totalVentas, 0) }}</h4>
</div>

@endsection