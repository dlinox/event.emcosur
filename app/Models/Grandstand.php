<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grandstand extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'location_reference',
        'capacity',
        'rows',
        'structure',
        'is_active',
        'event_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'structure' => 'json',
        'is_active' => 'boolean',
        'event_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_active',
        'location_reference',
        'description',
        'capacity',
        'structure'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // solo mostrar si esta activo en todas las consultas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    //Undefined array key 1
    public function seats()
    {   
        return $this->hasMany(Seat::class);
    }

    // tengo este error con este with : Undefined array key 1
    // protected $with = ['seats'];


}
