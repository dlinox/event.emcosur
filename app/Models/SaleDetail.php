<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    /*
 $table->unsignedBigInteger('seat_id');
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('restrict')->onUpdate('no action');
            $table->unsignedBigInteger('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('restrict')->onUpdate('no action');
   
    */

    protected $fillable = [
        'seat_id',
        'sale_id',
    ];

    protected $casts = [
        'seat_id' => 'integer',
        'sale_id' => 'integer',
    ];

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
