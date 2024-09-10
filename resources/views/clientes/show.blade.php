@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('content')
<style>
    .bg-light-red {
        background-color: #f8d7da !important; /* Rojo claro para "Vence hoy" */
        color: #721c24; /* Texto oscuro para contraste */
    }

    .bg-warning {
        background-color: #ffc107 !important; /* Naranja para advertencia */
        color: #856404; /* Texto oscuro para contraste */
    }

    .bg-danger {
        background-color: #dc3545 !important; /* Rojo fuerte para vencidas */
        color: #ffffff; /* Texto blanco */
    }

    .bg-success {
        background-color: #d4edda !important; /* Verde claro para pagadas */
        color: #155724; /* Texto verde oscuro */
    }
</style>

<div class="container mt-4">
    <h2>Detalles de {{ $cliente->nombre }}</h2>
    
    <br>  
    
    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('clientes.show', $cliente->id) }}" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="venta" class="form-control" placeholder="Venta ID" value="{{ request('venta') }}">
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-control">
                    <option value="">Seleccione Estado</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Pagada" {{ request('estado') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado_doc" class="form-control">
                    <option value="">Seleccione Estado Doc</option>
                    <option value="3_dias_restantes" {{ request('estado_doc') == '3_dias_restantes' ? 'selected' : '' }}>3 días restantes</option>
                    <option value="vencidas" {{ request('estado_doc') == 'vencidas' ? 'selected' : '' }}>Vencidas</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="documento" class="form-control" placeholder="Documento" value="{{ request('documento') }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    <br> 

    <!-- Mostrar las ventas del cliente -->
    <h3>Movimientos de Ventas</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Venta</th>
                <th>Fecha</th>
                <th>Fecha vencimiento</th>
                <th>Estado Doc</th>
                <th>Documento</th>
                <th>Total</th>
                <th>Bonificado</th>
                <th>Pendiente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Variables para los totales
                $totalVentas = 0;
                $totalBonificado = 0;
                $totalPendiente = 0;
            @endphp
            @foreach ($cliente->ventas as $venta)
                @php
                    $bonificado = $venta->pagos->sum('monto'); // Suma total de los pagos hechos a la venta
                    $pendiente = $venta->total - $bonificado; // Total pendiente por pagar

                    // Acumulación de los totales
                    $totalVentas += $venta->total;
                    $totalBonificado += $bonificado;
                    $totalPendiente += $pendiente;
                    $totalPendienteVencidas = 0;

                    // Calcular los días restantes para la fecha de vencimiento
                    $diasRestantes = floor(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($venta->fecha_vencimiento), false));


                    // Sumar al total de pendientes de vencidas si los días restantes son negativos (venta vencida) o es el día de vencimiento
                    if ($diasRestantes <= 0) {
                        $totalPendienteVencidas += $pendiente; // Sumar correctamente solo las ventas vencidas o las que vencen hoy
                    }
            
                    // Determinar la clase de color para la fila
                    $rowClass = '';
                    if ($venta->estado == 'Pagada') {
                        $rowClass = 'bg-success'; // Verde para Pagadas
                    } elseif ($diasRestantes > 3) {
                        $rowClass = ''; // Sin color
                    } elseif ($diasRestantes <= 3 && $diasRestantes >= 2) {
                        $rowClass = 'bg-warning'; // Naranja para 3 a 2 días restantes
                    } elseif ($diasRestantes == 0) {
                        $rowClass = 'bg-light-red'; // Rojo claro para "Vence hoy"
                    } elseif ($diasRestantes < 0) {
                        $rowClass = 'bg-danger'; // Rojo fuerte para vencidas
                    }

                    // Actualizar el estado a Pagada si el pendiente es 0
                    if ($pendiente <= 0) {
                        $venta->estado = 'Pagada';
                        $venta->save();
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $venta->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha_vencimiento)->format('d-m-Y') }}</td>
                    <td>
                        @if ($venta->estado == 'Pagada')
                            {{-- No mostrar nada si la venta está pagada --}}
                        @else
                            @if ($diasRestantes > 0)
                                {{ $diasRestantes }} días restantes
                            @elseif($diasRestantes == 0)
                                Vence hoy
                            @else
                                Vencido hace {{ abs($diasRestantes) }} días
                            @endif
                        @endif
                    </td>
                    <td>{{ $venta->documento }}</td>
                    <td>${{ number_format($venta->total, 0) }}</td>
                    <td>${{ number_format($bonificado, 0) }}</td>
                    <td>${{ number_format(max($pendiente, 0), 0) }}</td>
                    <td>
                        @if ($pendiente <= 0)
                            Pagada
                        @else
                            {{ $venta->estado }}
                        @endif
                    </td>

                    <td>
                        <!-- Menú de acciones desplegable similar a "Mantenedores" -->
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: black;">
                                    Acciones <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('ventas.show', $venta->id) }}">Ver Detalle de Venta</a></li>
                                    <li><a href="{{ route('ventas.showPayments', $venta->id) }}">Ver Detalle de Pagos</a></li>
                                    @if ($venta->estado !== 'Pagada')
                                        <li><a href="{{ route('ventas.addPayment', $venta->id) }}">Agregar Pago</a></li>
                                    @endif
                                    @if (empty($venta->documento))
                                        <li><a href="{{ route('ventas.addDocument', $venta->id) }}">Agregar Documento</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            <!-- Fila de totales -->
            <tr class="font-weight-bold">
                <td colspan="5">Totales</td>
                <td>${{ number_format($totalVentas, 0) }}</td>
                <td>${{ number_format($totalBonificado, 0) }}</td>
                <td>${{ number_format(max($pendiente, 0), 0) }}</td>
                <td colspan="3"></td>
            </tr>
            <!-- Fila del total de pendientes de las vencidas y vencen hoy -->
            <tr class="font-weight-bold">
                <td colspan="7">Total Pendiente de Ventas Vencidas</td>
                <td style="color: red;">${{ number_format(max($totalPendienteVencidas, 0), 0) }}</td> <!-- Monto en color rojo -->
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Scripts necesarios para dropdown -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
@endsection