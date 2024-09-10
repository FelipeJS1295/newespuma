<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConciliacionBancaria extends Model
{
    use HasFactory;

    protected $table = 'conciliacion_bancaria';

    protected $fillable = [
        'venta_id',
        'tipo_pago',
        'monto',
        'detalle',
        'fecha_pago',
        'tipo_movimiento',
        'diferencia',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
