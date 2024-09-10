@extends('layouts.app')

@section('title', 'Responder Ticket')

@section('content')
<div class="container mt-4">
    <h2>Responder Ticket: {{ $ticket->asunto }}</h2>
    <form method="POST" action="{{ route('tickets.respond', $ticket->id) }}">
        @csrf
        <div class="form-group">
            <label for="respuesta">Respuesta:</label>
            <textarea name="respuesta" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Responder</button>
    </form>
</div>
@endsection
