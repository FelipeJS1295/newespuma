<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Produccion;
use App\Models\MovimientoProduccion;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los filtros desde la solicitud
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $productoId = $request->input('producto_id');
    
        // Filtrar producciones según los filtros aplicados
        $producciones = Produccion::with('producto')
            ->when($fechaDesde, function ($query) use ($fechaDesde) {
                return $query->whereDate('created_at', '>=', $fechaDesde);
            })
            ->when($fechaHasta, function ($query) use ($fechaHasta) {
                return $query->whereDate('created_at', '<=', $fechaHasta);
            })
            ->when($productoId, function ($query) use ($productoId) {
                return $query->where('producto_id', $productoId);
            })
            ->get();
    
        // Crear un resumen del inventario agrupado por producto
        $resumenInventario = $producciones->groupBy('producto.id')->map(function ($items, $key) {
            $producto = $items->first()->producto;
            return [
                'nombre' => $producto ? $producto->nombre : 'Sin nombre',
                'total_cantidad' => $items->sum('cantidad'),
                'total_centimetros' => $items->sum('centimetros'),
            ];
        });
    
        // Filtrar movimientos según los filtros aplicados
        $movimientos = MovimientoProduccion::with('produccion.producto')
            ->when($fechaDesde, function ($query) use ($fechaDesde) {
                return $query->whereDate('created_at', '>=', $fechaDesde);
            })
            ->when($fechaHasta, function ($query) use ($fechaHasta) {
                return $query->whereDate('created_at', '<=', $fechaHasta);
            })
            ->when($productoId, function ($query) use ($productoId) {
                return $query->whereHas('produccion', function ($q) use ($productoId) {
                    $q->where('producto_id', $productoId);
                });
            })
            ->get();
    
        // Crear un resumen de los movimientos agrupados por producto
        $resumenMovimientos = $movimientos->groupBy('produccion.producto.id')->map(function ($items, $key) {
            $producto = $items->first()->produccion->producto;
            return [
                'nombre' => $producto ? $producto->nombre : 'Sin nombre',
                'total_centimetros' => $items->sum('centimetros'),
            ];
        });
    
        // Obtener la lista de productos para los filtros
        $productos = Producto::all();
    
        return view('inventarios.index', compact('producciones', 'resumenInventario', 'resumenMovimientos', 'productos', 'fechaDesde', 'fechaHasta', 'productoId'));
    }    

    public function create()
    {
        $productos = Producto::all();
        return view('inventarios.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        Inventario::create($request->all());

        return redirect()->route('inventarios.index')->with('success', 'Inventario agregado correctamente.');
    }

    public function edit(Inventario $inventario)
    {
        $productos = Producto::all();
        return view('inventarios.edit', compact('inventario', 'productos'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $inventario->update($request->all());

        return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado correctamente.');
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        return redirect()->route('inventarios.index')->with('success', 'Inventario eliminado correctamente.');
    }
}
