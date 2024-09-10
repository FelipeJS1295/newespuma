<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Mostrar todos los tickets
    public function index(Request $request)
    {
        // Obtener el número de elementos por página, por defecto 10
        $perPage = $request->input('per_page', 10);
    
        // Aplicar los filtros y la paginación
        $tickets = Ticket::when($request->input('estado'), function ($query, $estado) {
            return $query->where('estado', $estado);
        })->paginate($perPage); // Cambia a paginate() en lugar de get()
    
        return view('tickets.index', compact('tickets'));
    }

    // Mostrar el formulario para crear un nuevo ticket
    public function create()
    {
        return view('tickets.create');
    }

    // Guardar un nuevo ticket
    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        // Crear el ticket con el ID del usuario autenticado
        Ticket::create([
            'asunto' => $request->input('asunto'),
            'descripcion' => $request->input('descripcion'),
            'users_id' => Auth::id(), // Asignar el ID del usuario autenticado
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
    }

    // Mostrar el formulario para responder a un ticket
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    // Guardar la respuesta y cambiar el estado del ticket a 'Respondido'
    public function respond(Request $request, Ticket $ticket)
    {
        $request->validate([
            'respuesta' => 'required|string',
        ]);

        $ticket->update([
            'respuesta' => $request->input('respuesta'),
            'estado' => 'Respondido',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket respondido correctamente.');
    }

    public function respuesta($id)
    {
        // Encontrar el ticket por su ID
        $ticket = Ticket::findOrFail($id);

        // Retornar la vista con el ticket encontrado
        return view('tickets.respuesta', compact('ticket'));
    }
    
    public function guardarRespuesta(Request $request, $id)
    {
        // Validar la entrada
        $request->validate([
            'respuesta' => 'required|string',
        ]);

        // Encontrar el ticket y actualizar la respuesta
        $ticket = Ticket::findOrFail($id);
        $ticket->respuesta = $request->input('respuesta');
        $ticket->estado = 'Respondido'; // Cambiar el estado a 'Respondido'
        $ticket->save();

        // Redirigir al índice de tickets con un mensaje de éxito
        return redirect()->route('tickets.index')->with('success', 'Respuesta guardada correctamente.');
    }
}
