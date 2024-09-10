@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Cliente</h2>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf <!-- Token de seguridad de Laravel -->
        <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" name="rut" class="form-control" id="rut" placeholder="Ingrese el RUT del cliente" required>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre del cliente" required>
        </div>
        
        <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" name="contacto" class="form-control" id="contacto" placeholder="Ingrese el contacto del cliente" required>
        </div>
        
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" id="correo" placeholder="Ingrese el correo del cliente" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
    </form>
</div>
@endsection
