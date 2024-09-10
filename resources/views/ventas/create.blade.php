@extends('layouts.app')

@section('title', 'Nota de Venta')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-4">
    <h2>Nota de Venta</h2>
    
    <!-- Formulario de Nota de Venta -->
    <form method="POST" action="{{ route('ventas.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente_id" class="form-control">
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        
        <div class="form-group mb-3">
            <label for="dias_vencimiento">Días de Vencimiento:</label>
            <input type="number" id="dias_vencimiento" name="dias_vencimiento" class="form-control" min="1" value="30" required>
        </div>

        <!-- Selección de Productos -->
        <div class="form-group mb-3">
            <label for="producto">Producto:</label>
            <select id="producto" class="form-control">
                <option value="">Seleccione un producto</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}" 
                            data-precio="{{ $producto->precio }}" 
                            data-tipo="{{ $producto->tipo }}" 
                            data-disponible="{{ $producto->disponible }}">
                        {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-success mt-2" onclick="agregarProducto()">Agregar Producto</button>
        </div>

        <!-- Tabla de Productos Seleccionados -->
        <table class="table table-bordered" id="tablaProductos">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Nombre</th>
                    <th>por Bloque?</th>
                    <th>Pedido (cm)</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario (por cm)</th>
                    <th>Precio Total</th>
                    <th class="d-none" id="th-centimetros">Centímetros del Bloque</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas dinámicas serán añadidas aquí -->
            </tbody>
        </table>

        <!-- Resumen de la Venta -->
        <div class="mt-3">
            <p>Neto: $<span id="neto">0</span></p>
            <p>IVA (19%): $<span id="iva">0</span></p>
            <p>Total: $<span id="total">0</span></p>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<!-- Modal para selección de IVA -->
<div class="modal fade" id="ivaModal" tabindex="-1" aria-labelledby="ivaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ivaModalLabel">Seleccione Tipo de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Esta venta es con IVA o sin IVA?</p>
                <button type="button" class="btn btn-primary" onclick="guardarConIva(true)">Con IVA</button>
                <button type="button" class="btn btn-secondary" onclick="guardarConIva(false)">Sin IVA</button>
            </div>
        </div>
    </div>
</div>

<script>
    let contadorProductos = 0;

    function agregarProducto() {
    const productoSelect = document.getElementById('producto');
    const productoId = productoSelect.value;
    const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
    const productoPrecio = productoSelect.options[productoSelect.selectedIndex].getAttribute('data-precio');

    if (productoId === "") {
        alert("Por favor, seleccione un producto.");
        return;
    }

    contadorProductos++;
    const fila = `
        <tr id="producto-${contadorProductos}">
            <td>${contadorProductos}</td>
            <td>${productoNombre}</td>
            <td><input type="checkbox" id="check-${contadorProductos}" onclick="toggleCentimetros(${contadorProductos})"></td>
            <td>
                <input type="hidden" name="detalles[${contadorProductos}][producto_id]" value="${productoId}">
                <input type="number" name="detalles[${contadorProductos}][pedido_cm]" class="form-control" step="0.01" min="0" oninput="calcularTotal(${contadorProductos})">
            </td>
            <td><input type="number" name="detalles[${contadorProductos}][cantidad]" class="form-control" oninput="calcularTotal(${contadorProductos})"></td>
            <td><input type="number" name="detalles[${contadorProductos}][precio_unitario]" value="${productoPrecio}" class="form-control" oninput="calcularTotal(${contadorProductos})"></td>
            <td><input type="text" id="precioTotal-${contadorProductos}" class="form-control" readonly></td>
            <td class="d-none"><input type="number" name="detalles[${contadorProductos}][centimetros_bloque]" class="form-control" oninput="calcularTotal(${contadorProductos})"></td>
            <td><button type="button" class="btn btn-danger" onclick="eliminarFila(${contadorProductos})">Eliminar</button></td>
        </tr>
    `;
    document.querySelector("#tablaProductos tbody").insertAdjacentHTML('beforeend', fila);
    actualizarTotal();
}

    function toggleCentimetros(id) {
        const check = document.getElementById(`check-${id}`);
        const columnaCentimetros = document.querySelector(`#producto-${id} td:nth-child(8)`);

        if (check.checked) {
            columnaCentimetros.classList.remove('d-none');
        } else {
            columnaCentimetros.classList.add('d-none');
            document.querySelector(`input[name='detalles[${id}][centimetros_bloque]']`).value = 0; // Resetear a 0 si se desactiva
        }
        calcularTotal(id);
    }

    function calcularTotal(id) {
    const pedidoCm = parseFloat(document.querySelector(`input[name='detalles[${id}][pedido_cm]']`).value) || 0;
    const cantidad = parseFloat(document.querySelector(`input[name='detalles[${id}][cantidad]']`).value) || 0;
    const precioUnitario = parseFloat(document.querySelector(`input[name='detalles[${id}][precio_unitario]']`).value) || 0;
    const usarCmBloque = document.getElementById(`check-${id}`).checked;
    const centimetrosBloque = parseFloat(document.querySelector(`input[name='detalles[${id}][centimetros_bloque]']`).value) || 0;

    let precioTotal = 0;
    
    if (usarCmBloque) {
        if (centimetrosBloque > 0) {
            precioTotal = precioUnitario * centimetrosBloque;
        } else {
            precioTotal = 0;
        }
    } else {
        precioTotal = cantidad * pedidoCm * precioUnitario;
    }

    document.getElementById(`precioTotal-${id}`).value = precioTotal.toFixed(2);
    actualizarTotal();
}



    function eliminarFila(id) {
        document.getElementById(`producto-${id}`).remove();
        actualizarTotal();
    }

    function actualizarTotal() {
        let neto = 0;
        document.querySelectorAll('input[id^="precioTotal-"]').forEach(function (input) {
            neto += parseFloat(input.value) || 0;
        });
    
        const iva = neto * 0.19;
        const total = neto + iva;
    
        document.getElementById('neto').innerText = neto.toFixed(2);
        document.getElementById('iva').innerText = iva.toFixed(2);
        document.getElementById('total').innerText = total.toFixed(2);
    }

    function generarFilas() {
        const filas = [];
        document.querySelectorAll('#tablaProductos tbody tr').forEach(tr => {
            const nombre = tr.cells[1].textContent;
            const pedido = tr.querySelector('input[name^="detalles"][name$="[pedido_cm]"]').value;
            const cantidad = tr.querySelector('input[name^="detalles"][name$="[cantidad]"]').value;

            filas.push(`
                <tr>
                    <td>${nombre}</td>
                    <td>${pedido}</td>
                    <td>${cantidad}</td>
                </tr>
            `);
        });
        return filas.join('');
    }
</script>
@endsection
