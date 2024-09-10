@extends('layouts.app')

@section('title', 'Producir Productos')

@section('content')
<div class="container">
    <h2 class="mb-4 text-danger">Producir Productos</h2>
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Nombre del Producto</th>
                <th scope="col">Densidad</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->densidad }}</td>
                    <td>
                    <a href="{{ route('produccion.create', ['id' => $producto->id]) }}" class="btn btn-primary">
                        Producir
                    </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
