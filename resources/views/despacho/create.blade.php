@extends('layouts.app')

@section('title', 'Despachar Venta')

@section('content')
<div class="container mt-4">
    <h2>Detalle de Venta #{{ $venta->id }}</h2>

    {{-- Mostrar mensaje de error si existe --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('despacho.store', $venta->id) }}" id="despachoForm">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Centímetros</th>
                    <th>Despachado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->centimetros }}</td>
                    <td>
                        <!-- Enviar solo los productos seleccionados -->
                        <input type="checkbox" class="despacho-check" name="productos[{{ $detalle->producto->id }}]" value="{{ $detalle->cantidad * $detalle->centimetros }}" onchange="checkAllChecked()">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary" id="guardarButton" disabled>Guardar Despacho</button>
    </form>

    <script>
        // Función para habilitar el botón solo si hay checkboxes seleccionados
        function checkAllChecked() {
            const checkboxes = document.querySelectorAll('.despacho-check');
            const guardarButton = document.getElementById('guardarButton');
            guardarButton.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
        }
    </script>

    @endsection