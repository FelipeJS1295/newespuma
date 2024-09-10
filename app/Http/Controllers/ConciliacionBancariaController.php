<?php

namespace App\Http\Controllers;

use App\Models\ConciliacionBancaria;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ConciliacionBancariaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los filtros de la solicitud
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        $cliente_id = $request->input('cliente_id');
        $tipo_pago = $request->input('tipo_pago');
        $tipo_movimiento = $request->input('tipo_movimiento');
    
        // Consultar los movimientos con los filtros aplicados
        $movimientos = ConciliacionBancaria::with('venta.cliente')
            ->when($fecha_desde, function ($query) use ($fecha_desde) {
                return $query->whereDate('fecha_pago', '>=', $fecha_desde);
            })
            ->when($fecha_hasta, function ($query) use ($fecha_hasta) {
                return $query->whereDate('fecha_pago', '<=', $fecha_hasta);
            })
            ->when($cliente_id, function ($query) use ($cliente_id) {
                return $query->whereHas('venta', function ($q) use ($cliente_id) {
                    $q->where('cliente_id', $cliente_id);
                });
            })
            ->when($tipo_pago, function ($query) use ($tipo_pago) {
                return $query->where('tipo_pago', $tipo_pago);
            })
            ->when($tipo_movimiento, function ($query) use ($tipo_movimiento) {
                return $query->where('tipo_movimiento', $tipo_movimiento);
            })
            ->get();
    
        // Calcular los totales por tipo de pago
        $totalTransferencia = $movimientos->where('tipo_pago', 'transferencia')->sum('monto');
        $totalDebito = $movimientos->where('tipo_pago', 'debito')->sum('monto');
        $totalEfectivo = $movimientos->where('tipo_pago', 'efectivo')->sum('monto');
        $totalOrdenCompra = $movimientos->where('tipo_pago', 'orden de compra')->sum('monto');
        $totalEfectivoIva = $movimientos->where('tipo_pago', 'efectivo iva')->sum('monto');
        
        // Calcular el total combinado de efectivo y efectivo iva
        $totalEfectivoCaja = $totalEfectivo + $totalEfectivoIva;
        
        // Calcular el total general de todos los pagos
        $totalPagos = $movimientos->sum('monto');
    
        // Calcular el total de diferencias
        $totalDiferencias = $movimientos->sum('diferencia');
    
        // Obtener todos los clientes para los filtros
        $clientes = Cliente::all();
    
        // Retornar la vista con los movimientos y los filtros
        return view('conciliacion.index', compact('movimientos', 'totalTransferencia', 'totalDebito', 'totalEfectivo', 'totalOrdenCompra', 'totalEfectivoIva', 'clientes', 'totalEfectivoCaja', 'totalPagos', 'totalDiferencias'));
    }


    public function create()
    {
        return view('conciliacion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_pago' => 'required',
            'monto' => 'required|numeric|min:0',
            'detalle' => 'nullable|string',
            'fecha_pago' => 'required|date',
        ]);

        ConciliacionBancaria::create($request->all());
        return redirect()->route('conciliacion.index')->with('success', 'Movimiento agregado correctamente.');
    }

    public function edit($id)
    {
        $movimiento = ConciliacionBancaria::findOrFail($id);
        return view('conciliacion.edit', compact('movimiento'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_pago' => 'required',
            'monto' => 'required|numeric|min:0',
            'detalle' => 'nullable|string',
            'fecha_pago' => 'required|date',
        ]);

        $movimiento = ConciliacionBancaria::findOrFail($id);
        $movimiento->update($request->all());
        return redirect()->route('conciliacion.index')->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        ConciliacionBancaria::findOrFail($id)->delete();
        return redirect()->route('conciliacion.index')->with('success', 'Movimiento eliminado correctamente.');
    }

public function storeSalida(Request $request)
{
    // Validar los campos de entrada
    $request->validate([
        'monto' => 'required|numeric|min:0',
        'detalle' => 'required|string',
        'fecha_pago' => 'required|date',
    ]);

    // Calcular el total combinado de efectivo y efectivo iva disponible
    $totalEfectivoEntrada = ConciliacionBancaria::whereIn('tipo_pago', ['efectivo', 'efectivo iva'])
        ->where('tipo_movimiento', 'entrada')
        ->sum('monto');

    $totalEfectivoSalida = ConciliacionBancaria::whereIn('tipo_pago', ['efectivo', 'efectivo iva'])
        ->where('tipo_movimiento', 'salida')
        ->sum('monto');

    $totalEfectivoDisponible = $totalEfectivoEntrada - $totalEfectivoSalida;

    // Validar que el monto no exceda el total disponible
    if ($request->monto > $totalEfectivoDisponible) {
        return redirect()->back()->withErrors(['error' => 'El monto de salida no puede ser mayor al efectivo disponible.']);
    }

    // Guardar el monto como negativo para la salida
    ConciliacionBancaria::create([
        'venta_id' => null,
        'tipo_pago' => 'efectivo',
        'monto' => -abs($request->monto),  // Asegurarse de que el monto sea negativo
        'detalle' => $request->detalle,
        'fecha_pago' => $request->fecha_pago,
        'tipo_movimiento' => 'salida',
    ]);

    return redirect()->route('conciliacion.index')->with('success', 'Salida de efectivo registrada correctamente.');
}



    public function createSalida()
    {
        // Calcular el total de entradas y salidas en efectivo
        $totalEfectivoEntrada = ConciliacionBancaria::where('tipo_pago', 'efectivo')
            ->where('tipo_movimiento', 'entrada')
            ->sum('monto');

        $totalEfectivoSalida = ConciliacionBancaria::where('tipo_pago', 'efectivo')
            ->where('tipo_movimiento', 'salida')
            ->sum('monto');

        // Calcular el total disponible en efectivo
        $totalEfectivoDisponible = $totalEfectivoEntrada - $totalEfectivoSalida;

        // Usar dd() para depurar si el valor está correcto
        dd($totalEfectivoDisponible);

        // Retornar la vista con la variable correcta
        return view('conciliacion.create', compact('totalEfectivoDisponible'));
    }
}