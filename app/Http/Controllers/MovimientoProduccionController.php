<?php

namespace App\Http\Controllers;

use App\Models\MovimientoProduccion;
use Illuminate\Http\Request;

class MovimientoProduccionController extends Controller
{
    public function eliminarDuplicados()
    {
        // Obtener todos los registros de movimiento_produccions
        $registros = MovimientoProduccion::all();

        // Agrupar registros por produccion_id, centimetros, tipo_movimiento
        $agrupados = $registros->groupBy(function ($item) {
            return $item->produccion_id . '-' . $item->centimetros . '-' . $item->tipo_movimiento;
        });

        // Iterar sobre cada grupo y eliminar duplicados
        foreach ($agrupados as $grupo) {
            // Si hay más de un registro en el grupo, eliminar los duplicados y mantener solo uno
            if ($grupo->count() > 1) {
                // Mantener el primer registro y eliminar los otros
                $grupo->shift(); // Mantener el primer registro
                foreach ($grupo as $registro) {
                    $registro->delete(); // Eliminar los duplicados
                }
            }
        }

        return redirect()->back()->with('success', 'Registros duplicados eliminados correctamente.');
    }
}
