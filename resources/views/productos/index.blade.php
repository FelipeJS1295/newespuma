@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Productos</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Crear Producto</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Densidad</th>
                <th>Tipo</th>
                <th>Costo</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->densidad ?? 'N/A' }}</td>
                    <td>{{ $producto->tipo ?? 'N/A' }}</td>
                    <td>{{ $producto->costo ? '$' . number_format($producto->costo, 2) : 'N/A' }}</td>
                    <td>{{ '$' . number_format($producto->precio, 2) }}</td>
                    <td>
                        @if (in_array(auth()->user()->role, ['admin']))
                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        @endif
                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            @if (in_array(auth()->user()->role, ['admin']))
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
