@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Usuario</h2>
    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $usuario->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $usuario->email) }}" required>
        </div>
        
        <div class="form-group">
            <label for="password">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese una nueva contraseña">
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirme la nueva contraseña">
        </div>
        
        <div class="form-group">
            <label for="role">Rol</label>
            <select name="role" class="form-control" id="role">
                <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="user" {{ old('role', $usuario->role) == 'user' ? 'selected' : '' }}>Usuario</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
