@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('content')
<div class="container mt-4">
    <h2>Venta {{ $venta->id }}</h2>

    <!-- Información de la Venta -->
    <div class="mb-4">
        <p><strong>Cliente:</strong> {{ $venta->cliente->nombre }}</p>
        <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
        <p><strong>Total:</strong> ${{ number_format($venta->total, 0) }}</p>
    </div>

    <!-- Tabla de Detalles de la Venta -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Centímetros</th>
                <th>Precio Unitario (por cm)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $detalle)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->centimetros }}</td>
                <td>${{ number_format($detalle->precio_unitario, 0) }}</td>
                <td>${{ number_format($detalle->subtotal, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botón de Regresar -->
    <a href="javascript:void(0);" class="btn btn-primary" onclick="imprimirDetalle()">Imprimir</a>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Regresar</a>
</div>

<script>
function imprimirDetalle() {
    // Crear una ventana de impresión
    const ventanaImpresion = window.open('', '_blank', 'width=600,height=600');

    // Generar el contenido HTML para impresión
    const contenido = `
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 10px;
                    width: 58mm; /* Ancho del papel */
                }
                .item {
                    margin-bottom: 10px;
                }
                .item p {
                    margin: 2px 0;
                    font-size: 12px; /* Ajustar el tamaño de fuente si es necesario */
                }
                h2 {
                    font-size: 14px; /* Ajustar el tamaño del título si es necesario */
                    margin: 0 0 10px 0;
                }
                .info {
                    margin-bottom: 10px;
                }
                .info p {
                    margin: 0;
                }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            <h2>Venta {{ $venta->id }}</h2>
            <div class="info">
                <p><strong>Cliente:</strong> ${document.querySelector('p strong').nextSibling.nodeValue.trim()}</p>
                <p><strong>Fecha:</strong> ${document.querySelectorAll('p strong')[1].nextSibling.nodeValue.trim()}</p>
            </div>
            ${generarDetalle()}
        </body>
        </html>
    `;

    ventanaImpresion.document.open();
    ventanaImpresion.document.write(contenido);
    ventanaImpresion.document.close();
}

function generarDetalle() {
    let detalle = '';
    document.querySelectorAll('table tbody tr').forEach((fila, index) => {
        const columnas = fila.querySelectorAll('td');
        let producto = columnas[1].textContent;

        // Reemplazar la primera palabra con "Lamina"
        producto = producto.replace(/^\w+/, 'Lamina');

        const cantidad = columnas[2].textContent;
        const centimetros = columnas[3].textContent;

        detalle += `
            <div class="item">
                <p><strong>Item ${index + 1}:</strong></p>
                <p>${producto}</p>
                <p>Cantidad: ${cantidad}</p>
                <p>Centímetros: ${centimetros}</p>
            </div>
        `;
    });
    return detalle;
}
</script>


@endsection
