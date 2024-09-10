@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Clientes</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('clientes.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por RUT o Nombre" value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>
    <br>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>RUT</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->rut }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->contacto }}</td>
                <td>{{ $cliente->correo }}</td>
                <td>
                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info btn-sm">Ver</a>
                    @if (in_array(auth()->user()->role, ['admin']))
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    @endif
                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        @if (in_array(auth()->user()->role, ['admin']))
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</button>
                        @endif
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection