<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    // Muestra una lista de todos los productos en producción
    public function index()
    {
        // Obtener todos los productos para mostrarlos en la vista
        $productos = Producto::all();

        // Pasar los productos a la vista
        return view('produccion.index', compact('productos'));
    }

    // Muestra el formulario para crear un nuevo registro de producción
    public function create($id)
    {
        // Encuentra el producto por ID
        $producto = Producto::find($id);
    
        // Verifica si el producto existe
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }
    
        // Retorna la vista con el producto
        return view('produccion.create', compact('producto'));
    }

    // Guarda un nuevo registro de producción en la base de datos
    public function store(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'tipoProduccion' => 'required|string',
            'cantidadBloques' => 'nullable|integer',
            'centimetrosBloque' => 'nullable|integer',
            'cantidadLaminas' => 'nullable|integer',
            'centimetrosLamina' => 'nullable|integer',
            'cantidadNapas' => 'nullable|integer',
            'metrosNapa' => 'nullable|numeric',
            'cantidadColchones' => 'nullable|integer',
        ]);
    
        // Crear nueva producción
        $produccion = new Produccion();
        $produccion->producto_id = $id; // ID del producto
        $produccion->tipo = $request->input('tipoProduccion');
    
        // Asignar valores según el tipo de producción
        switch ($produccion->tipo) {
            case 'bloque':
                $produccion->cantidad = $request->input('cantidadBloques');
                $produccion->centimetros = $request->input('cantidadBloques') * $request->input('centimetrosBloque');
                break;
            case 'lamina':
                $produccion->cantidad = $request->input('cantidadLaminas');
                $produccion->centimetros = $request->input('cantidadLaminas') * $request->input('centimetrosLamina');
                break;
            case 'napa':
                $produccion->cantidad = $request->input('cantidadNapas');
                $produccion->metros = $request->input('metrosNapa');
                break;
            case 'colchon':
                $produccion->cantidad = $request->input('cantidadColchones');
                break;
        }
    
        $produccion->save();
    
        return redirect()->route('produccion.index')->with('success', 'Producción guardada exitosamente.');
    }
    


    // Muestra los detalles de una producción específica
    public function show(Produccion $produccion)
    {
        return view('produccion.show', compact('produccion'));
    }

    // Muestra el formulario para editar un registro de producción
    public function edit(Produccion $produccion)
    {
        $productos = Producto::all();
        return view('produccion.edit', compact('produccion', 'productos'));
    }

    // Actualiza un registro de producción en la base de datos
    public function update(Request $request, Produccion $produccion)
    {
        $validatedData = $request->validate(Produccion::rules());
        $produccion->update($validatedData);

        return redirect()->route('produccion.index')->with('success', 'Producción actualizada exitosamente.');
    }

    // Elimina un registro de producción de la base de datos
    public function destroy($id)
    {
        $produccion = Produccion::findOrFail($id);
        $produccion->delete();

        return redirect()->route('inventarios.index')->with('success', 'Producción eliminada con éxito.');
    }
}
