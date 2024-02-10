<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'place_of_residence',
        'is_active'
    ];

    protected $casts = [
        'phone' => 'integer',
        'place_of_residence' => 'string',
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'place_of_residence',
    ];

    public $headers =  [
        ['text' => "ID", 'value' => "id", 'short' => false, 'order' => 'ASC'],
        ['text' => "Nombre", 'value' => "name", 'short' => false, 'order' => 'ASC'],
        ['text' => "Apellido", 'value' => "last_name", 'short' => false, 'order' => 'ASC'],
        ['text' => "Tipo de Documento", 'value' => "document_type", 'short' => false, 'order' => 'ASC'],
        ['text' => "Número de Documento", 'value' => "document_number", 'short' => false, 'order' => 'ASC'],
        ['text' => "Correo Electrónico", 'value' => "email", 'short' => false, 'order' => 'ASC'],
        ['text' => "Teléfono", 'value' => "phone", 'short' => false, 'order' => 'ASC'],
    ];
}
