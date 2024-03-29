<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location_reference',
        'ubication',
        'image',
        'date',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    //grandstands


    public function grandstands()
    {
        return $this->hasMany(Grandstand::class);
    }

    //formato a la fecha humanizada 24 de septiembre de 2021 en es

    public function getDateAttribute($value)
    {
        //fecha en español
        return Carbon::parse($value)->locale('es')->isoFormat('LL');
    }

    //seats

    public function seats()
    {
        return $this->hasManyThrough(Seat::class, Grandstand::class);
    }
}
