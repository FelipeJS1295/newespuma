<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Muestra la lista de productos
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    // Muestra el formulario de creación de un producto
    public function create()
    {
        return view('productos.create');
    }

    // Guarda un nuevo producto en la base de datos
    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'codigo' => 'required|unique:productos|max:50',
            'nombre' => 'required|string|max:100',
            'densidad' => 'nullable|integer',
            'tipo' => 'nullable|in:bloque,lamina,napa,colchon',
            'costo' => 'nullable|numeric|min:0.00000001',
            'precio' => 'required|numeric|min:0.0000001',
        ]);
    
        // Creamos el nuevo producto
        Producto::create($request->all());
    
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    // Muestra un producto específico
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    // Muestra el formulario para editar un producto existente
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    // Actualiza un producto existente en la base de datos
    public function update(Request $request, Producto $producto)
    {
        // Validamos los datos del formulario
        $request->validate([
            'codigo' => 'required|max:50|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string|max:100',
            'densidad' => 'nullable|integer',
            'tipo' => 'nullable|in:bloque,lamina,napa,colchon',
            'costo' => 'nullable|numeric|min:0.00000001',
            'precio' => 'required|numeric|min:0.0000001',
        ]);
    
        // Actualizamos el producto
        $producto->update($request->all());
    
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    // Elimina un producto de la base de datos
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
