@extends('layouts.app')

@section('title', 'Agregar Pago')

@section('content')
<div class="container mt-4">
    <h2>Agregar Pago a la Venta #{{ $venta->id }}</h2>
    
    @php
        $bonificado = $venta->pagos->sum('monto'); // Suma total de los pagos hechos a la venta
        $pendiente = $venta->total - $bonificado; // Total pendiente por pagar
        $iva = 0.19;
    @endphp

    <!-- Mostrar el monto pendiente -->
    <div class="alert alert-info">
        <strong>Pendiente:</strong> $<span id="pendiente">{{ number_format($pendiente, 0) }}</span>
    </div>

    <form method="POST" action="{{ route('ventas.storePayment', $venta->id) }}">
        @csrf
        <div class="form-group mb-3">
            <label for="tipo_pago">Tipo de Pago:</label>
            <select id="tipo_pago" name="tipo_pago" class="form-control">
                <option value="transferencia">Transferencia</option>
                <option value="debito">Débito</option>
                <option value="efectivo">Efectivo</option>
                <option value="orden de compra">Orden de Compra</option>
                <option value="efectivo iva">Efectivo IVA</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="monto">Monto:</label>
            <input type="number" id="monto" name="monto" class="form-control" required min="0" step="0.01">
            <small class="text-muted">El monto no puede exceder el pendiente si selecciona "efectivo".</small>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" id="aplicar_iva" class="form-check-input">
            <label class="form-check-label" for="aplicar_iva">Aplicar IVA (19%) al pendiente</label>
        </div>

        <!-- Campo Diferencia -->
        <div class="form-group mb-3">
            <label for="diferencia">Diferencia:</label>
            <input type="number" id="diferencia" name="diferencia" class="form-control" min="0" step="0.01">
            <small class="text-muted">Ingrese el monto faltante que debe el cliente.</small>
        </div>

        <div class="form-group mb-3">
            <label for="detalle">Detalle:</label>
            <textarea id="detalle" name="detalle" class="form-control"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="fecha_pago">Fecha de Pago:</label>
            <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Pago</button>
    </form>
</div>

<!-- Script para manejar la actualización del monto pendiente -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoPagoSelect = document.getElementById('tipo_pago');
        const montoInput = document.getElementById('monto');
        const pendienteSpan = document.getElementById('pendiente');
        const aplicarIvaCheckbox = document.getElementById('aplicar_iva');
        let pendienteOriginal = {{ $pendiente }};
        const iva = {{ $iva }};

        function actualizarPendiente() {
            let pendiente = pendienteOriginal;

            // Si el tipo de pago es "efectivo", restamos el IVA del pendiente
            if (tipoPagoSelect.value === 'efectivo') {
                pendiente = pendienteOriginal / (1 + iva); // Restamos el IVA
            }

            // Si el checkbox de aplicar IVA está marcado y no es efectivo, agregamos el IVA
            if (aplicarIvaCheckbox.checked && tipoPagoSelect.value !== 'efectivo') {
                pendiente = pendienteOriginal * (1 + iva); // Agregamos el IVA
            }

            // Mostrar el pendiente actualizado
            pendienteSpan.textContent = pendiente.toFixed(0);

            // Si el tipo de pago es efectivo, limitar el input al pendiente sin IVA
            if (tipoPagoSelect.value === 'efectivo') {
                montoInput.max = pendiente.toFixed(2);
            } else {
                montoInput.removeAttribute('max'); // Si no es efectivo, se puede exceder el pendiente
            }
        }

        tipoPagoSelect.addEventListener('change', function() {
            actualizarPendiente();
        });

        aplicarIvaCheckbox.addEventListener('change', function() {
            actualizarPendiente();
        });

        montoInput.addEventListener('input', function() {
            const maxMonto = parseFloat(montoInput.max);
            const montoIngresado = parseFloat(montoInput.value);

            if (tipoPagoSelect.value === 'efectivo' && montoIngresado > maxMonto) {
                montoInput.setCustomValidity('El monto no puede exceder el pendiente de $' + maxMonto.toFixed(2));
            } else {
                montoInput.setCustomValidity('');
            }
        });

        actualizarPendiente(); // Inicializar la vista con el cálculo adecuado
    });
</script>
@endsection