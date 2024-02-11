<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'row',
        'number',
        'price',
        'status',
        'grandstand_id',
        'is_active',
        'is_used'
    ];

    protected $casts = [
        'number' => 'integer',
        'is_active' => 'boolean',
        'is_used' => 'boolean',
        'grandstand_id' => 'integer',
    ];

    protected $appends = [
        'grandstand_name',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        // 'is_active',
    ];

    public function getGrandstandNameAttribute()
    {
        return $this->grandstand->name;
    }

    public function grandstand()
    {
        return $this->belongsTo(Grandstand::class, 'grandstand_id');
    }
}
