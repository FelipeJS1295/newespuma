@extends('layouts.app')

@section('title', 'Despacho de Ventas')

@section('content')
<div class="container mt-4">
    <h2>Lista de Ventas para Despacho</h2>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('despacho.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <input type="number" name="numero" class="form-control" placeholder="Número" value="{{ request('numero') }}">
            </div>
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
            <div class="col-md-2">
                <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <select name="estado_despacho" class="form-control">
                    <option value="">Seleccione estado</option>
                    <option value="Pendiente" {{ request('estado_despacho') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Despachado" {{ request('estado_despacho') == 'Despachado' ? 'selected' : '' }}>Despachado</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('despacho.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
    <br>

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Número de Venta</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado de Despacho</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->estado_despacho }}</td>
                <td>
                    @if($venta->estado_despacho == 'Pendiente')
                        <a href="{{ route('despacho.create', $venta->id) }}" class="btn btn-primary btn-sm">Despachar</a>
                    @else

                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
