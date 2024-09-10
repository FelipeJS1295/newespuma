<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Muestra una lista de clientes
    public function index(Request $request)
    {
        // Obtener los filtros de la solicitud
        $search = $request->input('search');

        // Consultar los clientes con filtros aplicados
        $clientes = Cliente::when($search, function ($query) use ($search) {
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('rut', 'like', "%{$search}%");
        })->paginate(10); // Puedes ajustar el número de elementos por página

        return view('clientes.index', compact('clientes'));
    }

    // Muestra el formulario de creación de un cliente
    public function create()
    {
        return view('clientes.create');
    }

    // Guarda un nuevo cliente en la base de datos
    public function store(Request $request)
    {
        $validatedData = $request->validate(Cliente::rules());
        Cliente::create($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show($id, Request $request)
    {
        // Obtener el cliente y sus ventas aplicando los filtros
        $cliente = Cliente::findOrFail($id);
    
        // Obtener los filtros de la solicitud
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $ventaId = $request->input('venta');
        $estado = $request->input('estado');
        $estadoDoc = $request->input('estado_doc');
        $documento = $request->input('documento');
    
        // Filtrar las ventas del cliente según los filtros aplicados
        $ventas = Venta::where('cliente_id', $cliente->id)
            ->when($fechaDesde, function ($query) use ($fechaDesde) {
                return $query->whereDate('fecha', '>=', $fechaDesde);
            })
            ->when($fechaHasta, function ($query) use ($fechaHasta) {
                return $query->whereDate('fecha', '<=', $fechaHasta);
            })
            ->when($ventaId, function ($query) use ($ventaId) {
                return $query->where('id', $ventaId);
            })
            ->when($estado, function ($query) use ($estado) {
                return $query->where('estado', $estado);
            })
            ->when($estadoDoc, function ($query) use ($estadoDoc) {
                if ($estadoDoc === '3_dias_restantes') {
                    return $query->whereRaw('DATEDIFF(fecha_vencimiento, CURDATE()) <= 3')
                                 ->whereRaw('DATEDIFF(fecha_vencimiento, CURDATE()) >= 1');
                } elseif ($estadoDoc === 'vencidas') {
                    return $query->whereRaw('DATEDIFF(fecha_vencimiento, CURDATE()) < 0');
                }
            })
            ->when($documento, function ($query) use ($documento) {
                return $query->where('documento', 'like', "%$documento%");
            })
            ->with('pagos') // Asegurarse de cargar los pagos relacionados
            ->get();
    
        return view('clientes.show', compact('cliente', 'ventas'));
    }



    // Muestra el formulario de edición de un cliente
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // Actualiza un cliente existente en la base de datos
    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate(Cliente::rules());
        $cliente->update($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    // Elimina un cliente de la base de datos
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}