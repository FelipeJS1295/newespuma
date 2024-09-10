<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Definimos los atributos que se pueden asignar de forma masiva
    protected $fillable = ['codigo', 'nombre', 'densidad', 'tipo', 'costo', 'precio'];

    public $timestamps = false; // Desactiva las columnas created_at y updated_at

    // Añadimos validaciones de los atributos si es necesario
    public static function rules()
    {
        return [
            'codigo' => 'required|unique:productos,codigo|max:50',
            'nombre' => 'required|string|max:100',
            'densidad' => 'nullable|integer',
            'tipo' => 'nullable|in:bloque,lamina,napa,colchon',
            'costo' => 'nullable|numeric|min:0',
            'precio' => 'required|numeric|min:0',
        ];
    }

    public function produccions()
    {
        return $this->hasMany(Produccion::class, 'producto_id');
    }
}
