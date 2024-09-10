@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Producto</h2>
    <form action="{{ route('productos.store') }}" method="POST">
        @csrf <!-- Token de seguridad de Laravel -->
        <div class="form-group">
            <label for="codigo">Código del Producto</label>
            <input type="text" name="codigo" class="form-control" id="codigo" placeholder="Ingrese el código del producto" required>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre del producto" required>
        </div>
        
        <div class="form-group">
            <label for="densidad">Densidad (opcional)</label>
            <input type="number" name="densidad" class="form-control" id="densidad" placeholder="Ingrese la densidad del producto">
        </div>
        
        <div class="form-group">
            <label for="tipo">Tipo de Producto</label>
            <select name="tipo" class="form-control" id="tipo">
                <option value="">Seleccione un tipo</option>
                <option value="bloque">Bloque</option>
                <option value="lamina">Lámina</option>
                <option value="napa">Napa</option>
                <option value="colchon">Colchón</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="costo">Costo (opcional)</label>
            <input type="number" name="costo" class="form-control" id="costo" placeholder="Ingrese el costo del producto" step="0.00000001" min="0">
        </div>
        
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" name="precio" class="form-control" id="precio" placeholder="Ingrese el precio del producto" step="0.0000001" min="0" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Producto</button>
    </form>
</div>
@endsection