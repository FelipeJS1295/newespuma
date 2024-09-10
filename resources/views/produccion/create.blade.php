@extends('layouts.app')

@section('title', 'Producción de Producto')

@section('content')
<div class="container mt-4">
    <h2>Producción de {{ $producto->nombre }}</h2>

    <form method="POST" action="{{ route('produccion.store', ['id' => $producto->id]) }}">
        @csrf

        <!-- Selección del tipo de producción -->
        <div class="form-group mb-3">
            <label for="tipoProduccion">Tipo de Producción:</label>
            <select id="tipoProduccion" name="tipoProduccion" class="form-control" onchange="mostrarCampos()">
                <option value="">Seleccione un tipo</option>
                <option value="bloque">Bloque</option>
                <option value="lamina">Lámina</option>
                <option value="napa">Napa</option>
                <option value="colchon">Colchón</option>
            </select>
        </div>

        <!-- Campos para Producción de Bloque -->
        <div id="camposBloque" style="display: none;">
            <div class="form-group mb-3">
                <label for="cantidadBloques">Cantidad de Bloques:</label>
                <input type="number" class="form-control" id="cantidadBloques" name="cantidadBloques">
            </div>
            <div class="form-group mb-3">
                <label for="centimetrosBloque">Alto del Bloque:</label>
                <input type="number" class="form-control" id="centimetrosBloque" name="centimetrosBloque">
            </div>
        </div>

        <!-- Campos para Producción de Lámina -->
        <div id="camposLamina" style="display: none;">
            <div class="form-group mb-3">
                <label for="cantidadLaminas">Cantidad de Láminas:</label>
                <input type="number" class="form-control" id="cantidadLaminas" name="cantidadLaminas">
            </div>
            <div class="form-group mb-3">
                <label for="centimetrosLamina">Centímetros de la Lámina:</label>
                <input type="number" class="form-control" id="centimetrosLamina" name="centimetrosLamina">
            </div>
        </div>

        <!-- Campos para Producción de Napa -->
        <div id="camposNapa" style="display: none;">
            <div class="form-group mb-3">
                <label for="cantidadNapas">Cantidad de Napas:</label>
                <input type="number" class="form-control" id="cantidadNapas" name="cantidadNapas">
            </div>
            <div class="form-group mb-3">
                <label for="metrosNapa">Metros de Napa:</label>
                <input type="number" class="form-control" id="metrosNapa" name="metrosNapa">
            </div>
        </div>

        <!-- Campos para Producción de Colchón -->
        <div id="camposColchon" style="display: none;">
            <div class="form-group mb-3">
                <label for="cantidadColchones">Cantidad de Colchones:</label>
                <input type="number" class="form-control" id="cantidadColchones" name="cantidadColchones">
            </div>
        </div>

        <!-- Botón de envío -->
        <button type="submit" class="btn btn-primary">Guardar Producción</button>
    </form>
</div>

<script>
    function calcularTotal() {
        var tipo = document.getElementById('tipoProduccion').value;
        var total = 0;

        if (tipo === 'bloque') {
            var cantidadBloques = document.getElementById('cantidadBloques').value;
            var centimetrosBloque = document.getElementById('centimetrosBloque').value;
            total = cantidadBloques * centimetrosBloque;
        } else if (tipo === 'lamina') {
            var cantidadLaminas = document.getElementById('cantidadLaminas').value;
            var centimetrosLamina = document.getElementById('centimetrosLamina').value;
            total = cantidadLaminas * centimetrosLamina;
        } else if (tipo === 'napa') {
            var cantidadNapas = document.getElementById('cantidadNapas').value;
            var metrosNapa = document.getElementById('metrosNapa').value;
            total = cantidadNapas * metrosNapa;
        }

        // Mostrar el total en un campo o label (opcional)
        if (!isNaN(total) && total > 0) {
            document.getElementById('total').textContent = 'Total: ' + total;
        } else {
            document.getElementById('total').textContent = '';
        }
    }

    function mostrarCampos() {
        var tipo = document.getElementById('tipoProduccion').value;

        // Ocultar todos los campos
        document.getElementById('camposBloque').style.display = 'none';
        document.getElementById('camposLamina').style.display = 'none';
        document.getElementById('camposNapa').style.display = 'none';
        document.getElementById('camposColchon').style.display = 'none';

        // Mostrar los campos según el tipo seleccionado
        if (tipo === 'bloque') {
            document.getElementById('camposBloque').style.display = 'block';
        } else if (tipo === 'lamina') {
            document.getElementById('camposLamina').style.display = 'block';
        } else if (tipo === 'napa') {
            document.getElementById('camposNapa').style.display = 'block';
        } else if (tipo === 'colchon') {
            document.getElementById('camposColchon').style.display = 'block';
        }

        calcularTotal();
    }
</script>

@endsection
