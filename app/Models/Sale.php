<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'payment_type',
        'payment_method',
        'payment_date',
        'payment_transaction_id',
        'payment_amount',
        'payment_image',
        'payment_bank',
        'payment_currency',
        'is_active',
        'event_id',
        'customer_id',
        'user_id',
    ];

    protected $appends = [
        'payment_image_url',
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        // 'payment_amount' => 'double',
        'is_active' => 'boolean',
        'event_id' => 'integer',
        'customer_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //payment_image_url
    public function getPaymentImageUrlAttribute()
    {
        return $this->payment_image ? asset("uploads/$this->payment_image") : null;
    }
}
