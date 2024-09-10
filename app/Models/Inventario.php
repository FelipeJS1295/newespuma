<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'inventarios';

    // Campos asignables en masa
    protected $fillable = [
        'producto_id', 
        'cantidad',    
        'ubicacion',   
    ];

    /**
     * Relación con el modelo Producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con el modelo Produccion.
     */
    public function produccion()
    {
        return $this->hasMany(Produccion::class, 'producto_id', 'producto_id');
    }
}
