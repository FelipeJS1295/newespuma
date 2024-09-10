@extends('layouts.app')

@section('title', 'Lista de Ventas')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Ventas</h2>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('ventas.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="cliente_id" class="form-control">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <input type="number" name="numero" class="form-control" placeholder="Número de venta" value="{{ request('numero') }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
    <br>
    <!-- Botón para crear nueva venta -->
    <a href="{{ route('ventas.create') }}" class="btn btn-success mb-3">Crear Nueva Venta</a>
    <br></br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>${{ number_format($venta->total) }}</td>
                <td>
                    <!-- Aquí puedes agregar botones para editar o eliminar la venta -->
                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-primary btn-sm">Ver</a>
                    <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta venta?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection