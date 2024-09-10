<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\ConciliacionBancaria;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    // Mostrar todas las ventas
    public function index(Request $request)
    {
        // Obtener todos los filtros de la solicitud
        $cliente_id = $request->input('cliente_id');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $numero = $request->input('numero');

        // Consultar las ventas con filtros
        $ventas = Venta::with('cliente', 'detalles.producto')
            ->when($cliente_id, function ($query) use ($cliente_id) {
                return $query->where('cliente_id', $cliente_id);
            })
            ->when($fecha_desde, function ($query) use ($fecha_desde) {
                return $query->whereDate('fecha', '>=', $fecha_desde);
            })
            ->when($fecha_hasta, function ($query) use ($fecha_hasta) {
                return $query->whereDate('fecha', '<=', $fecha_hasta);
            })
            ->when($numero, function ($query) use ($numero) {
                return $query->where('id', $numero);
            })
            ->get();

        // Obtener todos los clientes para el filtro
        $clientes = Cliente::all();

        return view('ventas.index', compact('ventas', 'clientes'));
    }
    
    public function create()
    {
        // Obtener todos los clientes para el formulario de creación
        $clientes = Cliente::all();
    
        // Obtener todos los productos
        $productos = Producto::all();
    
        // Retornar la vista del formulario de creación
        return view('ventas.create', compact('clientes', 'productos'));
    }

    // Guardar una nueva venta
public function store(Request $request)
{
    try {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'dias_vencimiento' => 'required|integer|min:1', // Validación para los días de vencimiento
            'detalles' => 'required|array',  // Asegura que 'detalles' sea un array
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.pedido_cm' => 'required|numeric|min:0',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.centimetros_bloque' => 'nullable|numeric|min:0', // Validación opcional para centímetros del bloque
        ]);

        // Calcular la fecha de vencimiento sumando los días de vencimiento a la fecha de la venta
        $diasVencimiento = (int) $request->input('dias_vencimiento');
        $fechaVencimiento = \Carbon\Carbon::parse($request->fecha)->addDays($diasVencimiento);

        // Crear la venta con la fecha de vencimiento calculada
        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'fecha_vencimiento' => $fechaVencimiento, // Guardar la fecha de vencimiento
            'total' => 0, // Inicialmente en 0
            'iva' => 0,   // Inicialmente en 0
        ]);

        // Procesar los detalles de la venta
        $neto = 0;
        foreach ($validatedData['detalles'] as $detalle) {
            // Verificar si el checkbox está marcado y calcular subtotal en consecuencia
            if (isset($detalle['centimetros_bloque']) && $detalle['centimetros_bloque'] > 0) {
                $subtotal = $detalle['centimetros_bloque'] * $detalle['precio_unitario'];
            } else {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'] * $detalle['pedido_cm'];
            }

            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'centimetros' => $detalle['pedido_cm'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal' => $subtotal,
            ]);
            $neto += $subtotal;
        }

        // Calcular el IVA (19%)
        $iva = $neto * 0.19;

        // Calcular el total incluyendo el IVA
        $total = $neto + $iva;

        // Actualizar el total y el IVA de la venta
        $venta->update([
            'total' => $total,
            'iva' => $iva
        ]);

        return redirect()->route('clientes.show', $venta->cliente_id)
            ->with('success', 'Venta guardada correctamente con la fecha de vencimiento calculada.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}




    // Eliminar una venta
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }

    public function show($id)
    {
        $venta = Venta::with('cliente', 'detalles.producto')->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }


    public function storePago(Request $request, $id)
    {
        // Obtener la venta para redirigir correctamente
        $venta = Venta::findOrFail($id);

        // Crear el pago en la tabla conciliacion_bancaria
        ConciliacionBancaria::create([
            'venta_id' => $id,
            'tipo_pago' => $request->tipo_pago,
            'monto' => $request->monto,
            'detalle' => $request->detalle,
            'fecha_pago' => $request->fecha_pago,
        ]);

        return redirect()->route('clientes.show', $venta->cliente_id)
            ->with('success', 'Pago agregado correctamente.');
    }

    public function addPayment($id)
    {
        $venta = Venta::findOrFail($id);
        return view('ventas.agregar-pago', compact('venta'));
    }

    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'tipo_pago' => 'required',
            'monto' => 'required|numeric|min:0',
            'detalle' => 'nullable|string',
            'fecha_pago' => 'required|date',
            'diferencia' => 'nullable|numeric|min:0'
        ]);
    
        $venta = Venta::findOrFail($id);
    
        // Calcular el monto pendiente
        $bonificado = $venta->pagos->sum('monto');
        $pendiente = $venta->total - $bonificado;
    
        // Crear el nuevo pago
        ConciliacionBancaria::create([
            'venta_id' => $id,
            'tipo_pago' => $request->tipo_pago,
            'monto' => $request->monto,
            'detalle' => $request->detalle,
            'fecha_pago' => $request->fecha_pago,
            'diferencia' => $request->diferencia
        ]);
    
        if ($request->tipo_pago === 'efectivo') {
            // Si el tipo de pago es efectivo, restar el IVA al monto pendiente
            $iva = 0.19; // IVA del 19%
            $nuevoTotal = $pendiente / (1 + $iva); // Ajustar el total sin IVA
    
            // Actualizar el total de la venta
            $venta->total = $nuevoTotal;
            $venta->save(); // Guardar los cambios en la venta
        }
    
        return redirect()->route('clientes.show', $venta->cliente_id)
            ->with('success', 'Pago agregado correctamente.');
    }


    public function addDocument($id)
    {
        $venta = Venta::findOrFail($id);
        return view('ventas.agregar-documento', compact('venta'));
    }

    public function storeDocument(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'documento' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date'
        ]);
    
        // Encontrar la venta por ID
        $venta = Venta::findOrFail($id);
    
        // Actualizar los datos de la venta
        $venta->update([
            'documento' => $request->documento,
            'fecha_vencimiento' => $request->fecha_vencimiento
        ]);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('clientes.show', $venta->cliente_id)
            ->with('success', 'Documento y fecha de vencimiento agregados correctamente.');
    }


    public function showPayments($id)
    {
        $venta = Venta::with('pagos')->findOrFail($id);
        return view('ventas.show-pagos', compact('venta'));
    }
    
    public function cuadrar($id)
    {
        $venta = Venta::find($id);
    
        // Calcula el pendiente
        $pendiente = $venta->total - $venta->pagos->sum('monto');
        
        if ($pendiente < 0) {
            // Ajustar el pendiente a 0 (esto solo se usa para mostrar en la vista)
            $venta->update([
                'pendiente' => 0, // Si tienes un campo para esto en la base de datos
            ]);
        }
    
        return redirect()->route('ventas.show', $venta->id)->with('success', 'Pendiente cuadrado a 0.');
    }
    
    public function exportExcel()
    {
        return Excel::download(new VentasExport, 'ventas.xlsx');
    }
    
}