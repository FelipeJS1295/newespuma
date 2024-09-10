@extends('layouts.app')

@section('title', 'Responder Ticket')

@section('content')
<div class="container mt-4">
    <h2>Responder al Ticket #{{ $ticket->id }}</h2>

    <!-- Mostrar el mensaje del ticket -->
    <div class="card mb-3">
        <div class="card-header">
            Asunto: {{ $ticket->asunto }}
        </div>
        <div class="card-body">
            <p>{{ $ticket->descripcion }}</p>
        </div>
    </div>

    <!-- Formulario para agregar la respuesta -->
    <form action="{{ route('tickets.guardarRespuesta', $ticket->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="respuesta">Respuesta:</label>
            <textarea class="form-control" id="respuesta" name="respuesta" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Respuesta</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
