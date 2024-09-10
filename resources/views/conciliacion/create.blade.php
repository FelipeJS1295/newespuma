@extends('layouts.app')

@section('title', 'Registrar Salida de Efectivo')

@section('content')
<div class="container mt-4">
    <h2 class="text-danger">Registrar Salida de Efectivo</h2>

    <!-- Formulario para registrar la salida de efectivo -->
    <form method="POST" action="{{ route('conciliacion.storeSalida') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="monto">Monto a Retirar:</label>
            <input type="number" id="monto" name="monto" class="form-control" required step="0.01"
                   placeholder="Ingrese el monto">
        </div>

        <div class="form-group mb-3">
            <label for="detalle">Detalle:</label>
            <textarea id="detalle" name="detalle" class="form-control" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="fecha_pago">Fecha:</label>
            <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Salida</button>
    </form>
</div>
@endsection
