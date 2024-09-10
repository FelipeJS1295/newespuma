@extends('layouts.app')

@section('title', 'Agregar Documento')

@section('content')
<div class="container mt-4">
    <h2>Agregar Documento a la Venta #{{ $venta->id }}</h2>
    
    <form method="POST" action="{{ route('ventas.storeDocument', $venta->id) }}">
        @csrf
        <div class="form-group mb-3">
            <label for="documento">Número de Documento:</label>
            <input type="text" id="documento" name="documento" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Documento</button>
    </form>
</div>
@endsection
