<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\MovimientoProduccion;
use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Mostrar la vista principal de reportes
    public function index()
    {
        // Obtener datos básicos para los filtros de cada reporte, si es necesario
        return view('reportes.index');
    }

    // Reporte de Producción
    public function reporteProduccion()
    {
        $producciones = Produccion::with('producto')->get();
        return view('reportes.produccion', compact('producciones'));
    }

    // Reporte de Movimientos de Inventario
    public function reporteMovimientos()
    {
        $movimientos = MovimientoProduccion::with('produccion.producto')->get();
        return view('reportes.movimientos', compact('movimientos'));
    }

    // Reporte de Ventas
    public function reporteVentas(Request $request)
    {
        // Obtener los filtros desde la solicitud
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $clienteId = $request->input('cliente');
    
        // Consultar ventas con los filtros aplicados
        $query = Venta::query();
    
        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
    
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }
    
        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }
    
        $ventas = $query->with('detalles.producto', 'cliente')->get();
    
        // Calcular la suma total de todas las ventas
        $totalVentas = 0;
        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $totalVentas += $detalle->subtotal;
            }
        }
    
        $clientes = Cliente::all(); // Obtener todos los clientes para el select
    
        // Pasar el total a la vista junto con las ventas y los clientes
        return view('reportes.ventas', compact('ventas', 'clientes', 'totalVentas'));
    }


    // Reporte de Despachos
    public function reporteDespachos()
    {
        // Puedes ajustar la consulta según cómo estés manejando los despachos
        $despachos = MovimientoProduccion::where('tipo_movimiento', 'despacho')->with('produccion.producto')->get();
        return view('reportes.despachos', compact('despachos'));
    }

    // Reporte de Resumen Financiero
    public function reporteFinanciero()
    {
        // Aquí se calcula el flujo de caja basado en ingresos y egresos de la tabla correspondiente
        $movimientos = MovimientoProduccion::all();
        $ingresos = $movimientos->where('tipo_movimiento', 'produccion')->sum('centimetros'); // Ajustar según tus necesidades
        $egresos = $movimientos->where('tipo_movimiento', 'despacho')->sum('centimetros');

        $flujoCaja = $ingresos - $egresos;

        return view('reportes.financiero', compact('ingresos', 'egresos', 'flujoCaja'));
    }
}
