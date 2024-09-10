@extends('layouts.app')

@section('title', 'Crear Ticket')

@section('content')
<div class="container mt-4">
    <h2>Crear Ticket</h2>
    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="form-group">
            <label for="asunto">Asunto:</label>
            <input type="text" name="asunto" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Crear Ticket</button>
    </form>
</div>
@endsection
