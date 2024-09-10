@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Reportes</h2>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#resumen" data-toggle="tab">Resumen</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#produccion" data-toggle="tab">Producción</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#movimientos" data-toggle="tab">Movimientos de Inventario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#ventas" data-toggle="tab">Ventas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#despachos" data-toggle="tab">Despachos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#financiero" data-toggle="tab">Resumen Financiero</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Resumen General -->
        <div class="tab-pane fade show active" id="resumen">
            <h4>Resumen General</h4>
            <p>Selecciona una pestaña para ver los detalles de cada reporte.</p>
        </div>

        <!-- Reporte de Producción -->
        <div class="tab-pane fade" id="produccion">
            <h4>Reporte de Producción</h4>
            <a href="{{ route('reportes.produccion') }}" class="btn btn-primary">Ver Reporte Completo</a>
        </div>

        <!-- Reporte de Movimientos de Inventario -->
        <div class="tab-pane fade" id="movimientos">
            <h4>Reporte de Movimientos de Inventario</h4>
            <a href="{{ route('reportes.movimientos') }}" class="btn btn-primary">Ver Reporte Completo</a>
        </div>

        <!-- Reporte de Ventas -->
        <div class="tab-pane fade" id="ventas">
            <h4>Reporte de Ventas</h4>
            <a href="{{ route('reportes.ventas') }}" class="btn btn-primary">Ver Reporte Completo</a>
        </div>

        <!-- Reporte de Despachos -->
        <div class="tab-pane fade" id="despachos">
            <h4>Reporte de Despachos</h4>
            <a href="{{ route('reportes.despachos') }}" class="btn btn-primary">Ver Reporte Completo</a>
        </div>

        <!-- Reporte de Resumen Financiero -->
        <div class="tab-pane fade" id="financiero">
            <h4>Resumen Financiero</h4>
            <a href="{{ route('reportes.financiero') }}" class="btn btn-primary">Ver Reporte Completo</a>
        </div>
    </div>
</div>

<!-- Agregar scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
