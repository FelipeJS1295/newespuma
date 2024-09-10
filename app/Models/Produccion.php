<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'produccions';

    // Campos asignables en masa
    protected $fillable = [
        'producto_id', // Relación con la tabla productos
        'tipo',        // tipo de producción (bloque, lámina, napa, colchón)
        'cantidad',    // cantidad de productos producidos
        'centimetros', // centímetros utilizados (si aplica)
        'metros',      // metros utilizados (si aplica)
    ];

    /**
     * Relación con el modelo Producto.
     * Una producción pertenece a un producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    // Método booted para manejar eventos Eloquent
    protected static function booted()
    {
        static::created(function ($produccion) {
            // Verificar si ya existe un registro en movimiento_produccions
            $existeMovimiento = MovimientoProduccion::where('produccion_id', $produccion->id)->exists();
    
            if (!$existeMovimiento) {
                // Crear registro solo si no existe uno asociado
                MovimientoProduccion::create([
                    'produccion_id' => $produccion->id,
                    'cantidad' => $produccion->cantidad, // Registrar la cantidad producida
                    'centimetros' => $produccion->centimetros, // Registrar los centímetros producidos
                    'tipo_movimiento' => 'produccion', // Tipo de movimiento
                    'detalle' => 'Registro automático de producción', // Detalle del movimiento
                ]);
            }
        });
    }
}
