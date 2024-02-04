<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /*
$table->string('name');
            $table->text('description')->nullable();
            $table->string('location_reference')->default('');
            $table->char('ubication')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(1);
            $table->date('date');

    */

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

        setlocale(LC_TIME, 'es_ES.UTF-8');



        return \Carbon\Carbon::parse($value)->format('d \d\e F \d\e Y');
    }

    //seats

    public function seats()
    {
        return $this->hasManyThrough(Seat::class, Grandstand::class);
    }
}
