@extends('layouts.app')

@section('title', 'Conciliación Bancaria')

@section('content')
<div class="container mt-4">
    <h2>Conciliación Bancaria</h2>
    <a href="{{ route('conciliacion.create') }}" class="btn btn-primary mb-3">Agregar Movimiento</a>
    <br></br>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('conciliacion.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
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
                <select name="tipo_pago" class="form-control">
                    <option value="">Tipo de Pago</option>
                    <option value="transferencia" {{ request('tipo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="debito" {{ request('tipo_pago') == 'debito' ? 'selected' : '' }}>Débito</option>
                    <option value="efectivo" {{ request('tipo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="orden de compra" {{ request('tipo_pago') == 'orden de compra' ? 'selected' : '' }}>Orden de Compra</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="tipo_movimiento" class="form-control">
                    <option value="">Tipo de Movimiento</option>
                    <option value="entrada" {{ request('tipo_movimiento') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="salida" {{ request('tipo_movimiento') == 'salida' ? 'selected' : '' }}>Salida</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('conciliacion.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
    <br>

    <!-- Tabla de Movimientos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Tipo de Pago</th>
                <th>Tipo de Movimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movimientos as $movimiento)
            <tr>
                <td>{{ \Carbon\Carbon::parse($movimiento->fecha_pago)->format('d-m-Y') }}</td>
                <td>{{ $movimiento->venta->cliente->nombre ?? 'N/A' }}</td>
                <td>${{ number_format($movimiento->monto, 2) }}</td>
                <td>{{ ucfirst($movimiento->tipo_pago) }}</td>
                <td>{{ ucfirst($movimiento->tipo_movimiento) }}</td> <!-- Nueva columna -->
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales por Tipo de Pago -->
    <div class="mt-4">
        <h4>Totales por Tipo de Pago:</h4>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Transferencia
                <span>${{ number_format($totalTransferencia, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Débito
                <span>${{ number_format($totalDebito, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Varios
                <span>${{ number_format($totalEfectivo, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Varios +
                <span>${{ number_format($totalEfectivoIva, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Orden de Compra
                <span>${{ number_format($totalOrdenCompra, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Total Efectivo Caja
                <span>${{ number_format($totalEfectivoCaja, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>Total Pagos</strong>
                <strong>${{ number_format($totalPagos, 2) }}</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>Total Diferencias</strong>
                <strong>${{ number_format($totalDiferencias, 2) }}</strong>
            </li>
        </ul>
    </div>
</div>
@endsection
