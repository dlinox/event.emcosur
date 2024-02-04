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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    //seats

    //Undefined array key 1
    public function seats()
    {   
        return $this->hasMany(Seat::class);
    }

    // tengo este error con este with : Undefined array key 1
    // protected $with = ['seats'];


}
