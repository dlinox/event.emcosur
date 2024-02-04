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
        'is_active',
        'grandstand_id',
    ];

    protected $casts = [
        'number' => 'integer',
        'is_active' => 'boolean',
        'grandstand_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_active',
    ];

    public function grandstand()
    {
        return $this->belongsTo(Grandstand::class, 'grandstand_id');
    }
}
