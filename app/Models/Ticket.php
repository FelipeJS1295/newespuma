<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'tickets';

    // Campos asignables en masa
    protected $fillable = [
        'asunto',
        'descripcion',
        'estado',
        'respuesta',
        'users_id', // Agrega este campo
    ];

    // Valores por defecto para algunos campos
    protected $attributes = [
        'estado' => 'Pendiente', // Estado inicial del ticket
    ];

    // Define una relación con el usuario que creó el ticket
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
