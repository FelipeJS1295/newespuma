@extends('layouts.app')

@section('title', 'Gestión de Tickets')

@section('content')
<div class="container mt-4">
    <h2>Gestión de Tickets</h2>

    <!-- Botón para crear un nuevo ticket -->
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Crear Ticket</a>
    <br></br>
    <!-- Mensaje de éxito -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filtros de tickets -->
    <form method="GET" action="{{ route('tickets.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="estado" class="form-control">
                    <option value="">Filtrar por Estado</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Respondido" {{ request('estado') == 'Respondido' ? 'selected' : '' }}>Respondido</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
    <br></br>
    <!-- Tabla de Tickets -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Asunto</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->user->name ?? 'Usuario no asignado' }}</td>
                <td>{{ $ticket->asunto }}</td>
                                <td>
                    @if ($ticket->estado == 'Respondido')
                        {{ $ticket->respuesta }}
                    @else
                        {{ $ticket->descripcion }}
                    @endif
                </td>
                <td>{{ $ticket->estado }}</td>
                <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    @if (in_array(auth()->user()->role, ['admin']))
                    @if($ticket->estado == 'Pendiente')
                    <a href="{{ route('tickets.respuesta', $ticket->id) }}" class="btn btn-warning btn-sm">Responder</a>
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
