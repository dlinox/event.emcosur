<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /*
            $table->string('name');
            $table->string('last_name');
            $table->enum('document_type', ['DNI', 'RUC', 'CE', 'PASSPORT'])->default('DNI');
            $table->string('document_number');
            $table->string('email');
            $table->integer('phone');
            $table->string('place_of_residence')->nullable();
            $table->boolean('is_active')->default(true);

    */

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
}
