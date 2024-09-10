@extends('layouts.app')

@section('title', 'Inventarios - Producciones')

@section('content')

        <!-- Formulario de filtros -->
        <form method="GET" action="{{ route('inventarios.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="fecha_desde">Fecha Desde:</label>
                <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_hasta">Fecha Hasta:</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <label for="producto_id">Producto:</label>
                <select name="producto_id" id="producto_id" class="form-control">
                    <option value="">Seleccione un Producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
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

    <br></br>

<div class="container mt-4">
    <h2 class="text-danger">Inventarios - Producciones</h2>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#inventario" data-toggle="tab">Inventario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#resumen" data-toggle="tab">Inventario Resumido</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#movimientos" data-toggle="tab">Movimientos</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Inventario normal -->
        <div class="tab-pane fade show active" id="inventario">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Centímetros / Metros</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($producciones as $produccion)
                            <tr>
                                <td>{{ $produccion->producto ? $produccion->producto->nombre : 'Producto no disponible' }}</td>
                                <td>{{ $produccion->tipo }}</td>
                                <td>{{ $produccion->cantidad }}</td>
                                <td>{{ $produccion->centimetros }} cm</td>
                                <td>{{ \Carbon\Carbon::parse($produccion->created_at)->format('d-m-Y') }}</td>
                                @if (in_array(auth()->user()->role, ['admin']))
                                <td>
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>

        <!-- Inventario resumido -->
        <div class="tab-pane fade" id="resumen">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Total Cantidad</th>
                        <th>Total Centímetros / Metros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resumenInventario as $producto => $resumen)
                    <tr>
                        <td>{{ $resumen['nombre'] }}</td>
                        <td>{{ $resumen['total_cantidad'] }}</td>
                        <td>{{ $resumen['total_centimetros'] }} cm</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<!-- Movimientos Resumidos -->
<div class="tab-pane fade" id="movimientos">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Total Centímetros / Metros</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resumenMovimientos as $producto => $resumen)
            <tr>
                <td>{{ $resumen['nombre'] }}</td>
                <td>{{ $resumen['total_centimetros'] }} cm</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


    </div>
</div>

<!-- Agregar scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
