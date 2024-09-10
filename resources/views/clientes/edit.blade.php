@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Cliente</h2>
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Método HTTP para la actualización -->

        <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" name="rut" class="form-control" id="rut" value="{{ old('rut', $cliente->rut) }}" required>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
        </div>
        
        <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" name="contacto" class="form-control" id="contacto" value="{{ old('contacto', $cliente->contacto) }}" required>
        </div>
        
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" id="correo" value="{{ old('correo', $cliente->correo) }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
