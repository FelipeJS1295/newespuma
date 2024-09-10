<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Definimos los atributos que se pueden asignar de forma masiva
    protected $fillable = ['rut', 'nombre', 'contacto', 'correo'];

    // Añadimos validaciones de los atributos si es necesario
    public static function rules()
    {
        return [
            'rut' => 'required|unique:clientes,rut|max:15',
            'nombre' => 'required|string|max:100',
            'contacto' => 'required|string|max:50',
            'correo' => 'required|email|unique:clientes,correo|max:100',
        ];
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
