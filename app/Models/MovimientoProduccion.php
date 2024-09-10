<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoProduccion extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'movimiento_produccions';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'produccion_id',
        'cantidad',
        'centimetros',
        'tipo_movimiento',
        'detalle',
    ];

    // Relación con el modelo Produccion
    public function produccion()
    {
        return $this->belongsTo(Produccion::class);
    }
}
