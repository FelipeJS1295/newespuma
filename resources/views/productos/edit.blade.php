@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Producto</h2>
    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Método HTTP para la actualización -->

        <div class="form-group">
            <label for="codigo">Código del Producto</label>
            <input type="text" name="codigo" class="form-control" id="codigo" value="{{ old('codigo', $producto->codigo) }}" required>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
        </div>
        
        <div class="form-group">
            <label for="densidad">Densidad (opcional)</label>
            <input type="number" name="densidad" class="form-control" id="densidad" value="{{ old('densidad', $producto->densidad) }}">
        </div>
        
        <div class="form-group">
            <label for="tipo">Tipo de Producto</label>
            <select name="tipo" class="form-control" id="tipo">
                <option value="" {{ old('tipo', $producto->tipo) == '' ? 'selected' : '' }}>Seleccione un tipo</option>
                <option value="bloque" {{ old('tipo', $producto->tipo) == 'bloque' ? 'selected' : '' }}>Bloque</option>
                <option value="lamina" {{ old('tipo', $producto->tipo) == 'lamina' ? 'selected' : '' }}>Lámina</option>
                <option value="napa" {{ old('tipo', $producto->tipo) == 'napa' ? 'selected' : '' }}>Napa</option>
                <option value="colchon" {{ old('tipo', $producto->tipo) == 'colchon' ? 'selected' : '' }}>Colchón</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="costo">Costo (opcional)</label>
            <input type="number" name="costo" class="form-control" id="costo" value="{{ old('costo', $producto->costo) }}" step="0.00000001" min="0">
        </div>
        
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" name="precio" class="form-control" id="precio" value="{{ old('precio', $producto->precio) }}" step="0.0000001" min="0" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
