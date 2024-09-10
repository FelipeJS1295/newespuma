@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Usuario</h2>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Ingrese el nombre del usuario" required>
        </div>
        
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese el correo electrónico del usuario" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese una contraseña" required>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirme la contraseña" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
    </form>
</div>
@endsection
