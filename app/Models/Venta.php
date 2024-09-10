<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    // Campos que se permiten para asignación masiva
    protected $fillable = [
        'cliente_id',
        'fecha',
        'total',
        'estado',
        'documento',
        'iva',
        'fecha_vencimiento', // Nueva columna agregada para asignación masiva
    ];

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con el modelo DetalleVenta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Relación con el modelo ConciliacionBancaria
    public function conciliaciones()
    {
        return $this->hasMany(ConciliacionBancaria::class);
    }

    public function pagos()
    {
        return $this->hasMany(ConciliacionBancaria::class, 'venta_id');
    }
}

