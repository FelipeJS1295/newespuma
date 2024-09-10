<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Produccion;
use App\Models\Cliente;
use App\Models\MovimientoProduccion;
use Illuminate\Http\Request;

class DespachoController extends Controller
{
    // Mostrar la lista de todas las ventas
    public function index(Request $request)
    {
        // Obtener los filtros de la solicitud
        $numero = $request->input('numero');
        $cliente_id = $request->input('cliente_id');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $estado_despacho = $request->input('estado_despacho');

        // Consultar las ventas con los filtros aplicados
        $ventas = Venta::with('cliente')
            ->when($numero, function ($query) use ($numero) {
                return $query->where('id', $numero);
            })
            ->when($cliente_id, function ($query) use ($cliente_id) {
                return $query->where('cliente_id', $cliente_id);
            })
            ->when($fecha_desde, function ($query) use ($fecha_desde) {
                return $query->whereDate('fecha', '>=', $fecha_desde);
            })
            ->when($fecha_hasta, function ($query) use ($fecha_hasta) {
                return $query->whereDate('fecha', '<=', $fecha_hasta);
            })
            ->when($estado_despacho, function ($query) use ($estado_despacho) {
                return $query->where('estado_despacho', $estado_despacho);
            })
            ->get();

        // Obtener todos los clientes para los filtros
        $clientes = Cliente::all();

        // Retornar la vista con los datos de ventas y los clientes
        return view('despacho.index', compact('ventas', 'clientes'));
    }


    public function create(Venta $venta)
    {
        // Cargar los detalles de la venta con los productos
        $venta->load('detalles.producto');

        return view('despacho.create', compact('venta'));
    }

    public function store(Request $request, $ventaId)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'productos' => 'required|array', // Solo se envían productos marcados
            ]);

            // Encontrar la venta por su ID
            $venta = Venta::findOrFail($ventaId);

            // Procesar cada producto seleccionado en el despacho
            foreach ($request->productos as $productoId => $centimetros) {
                // Encontrar la producción correspondiente
                $produccion = Produccion::where('producto_id', $productoId)->first();

                if ($produccion) {
                    // Crear un movimiento en la tabla 'movimiento_produccions'
                    MovimientoProduccion::create([
                        'produccion_id' => $produccion->id,
                        'cantidad' => 0, // No estamos afectando la cantidad, solo los centímetros
                        'centimetros' => -abs($centimetros), // Registrar los centímetros como negativos
                        'tipo_movimiento' => 'despacho',
                        'detalle' => 'Despacho registrado automáticamente',
                    ]);

                    // Eliminar el detalle de la venta después de despachar
                    $venta->detalles()->where('producto_id', $productoId)->delete();
                } else {
                    // Error específico si la producción no se encuentra
                    return redirect()->route('despacho.create', $ventaId)->with('error', 'Producción no encontrada para el producto ID: ' . $productoId);
                }
            }

            // Actualizar el estado de la venta si todos los productos han sido despachados
            if ($venta->detalles()->count() == 0) {
                $venta->estado_despacho = 'Despachado';
                $venta->save();
            }

            // Redirigir con un mensaje de éxito
            return redirect()->route('despacho.index')->with('success', 'Despacho guardado correctamente y movimientos registrados.');
        } catch (\Exception $e) {
            // Mostrar el mensaje de error en pantalla
            return redirect()->route('despacho.create', $ventaId)->with('error', 'Ha ocurrido un error: ' . $e->getMessage());
        }
    }

    private function todosDespachados($venta)
    {
        // Lógica para comprobar si todos los productos de la venta han sido despachados
        // Podrías contar los detalles despachados y compararlo con el total de detalles de la venta
        return $venta->detalles->every(function ($detalle) {
            return MovimientoProduccion::where('produccion_id', $detalle->producto->produccion->id)
                ->where('tipo_movimiento', 'despacho')
                ->exists();
        });
    }
}