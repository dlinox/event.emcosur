<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSchedule extends Model
{
    use HasFactory;

     protected $fillable = [
        'start',
        'end',
        'day',
        'is_available'
     ];

     protected $casts = [
        'start' => 'time',
        'end' => 'time',
        'day' => 'date',
        'is_available' => 'boolean'
     ];



}
