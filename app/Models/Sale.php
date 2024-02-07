<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    //payment_image_url
    public function getPaymentImageUrlAttribute()
    {
        return $this->payment_image ? asset("uploads/$this->payment_image") : null;
    }

    //fecha de pago
    public function getPaymentDateAttribute($value)
    {

        //solo mes y dia

        // return Carbon::parse($value)->locale('es')->isoFormat('LL');
        return Carbon::parse($value)->locale('es')->isoFormat('LL');

    }

    public $headers =  [
        ['text' => "ID", 'value' => "id", 'short' => false, 'order' => 'ASC'],
        ['text' => "Estado", 'value' => "status", 'short' => false, 'order' => 'ASC'],
        ['text' => "Tipo de pago", 'value' => "payment_type", 'short' => false, 'order' => 'ASC'],
        // ['text' => "Método de pago", 'value' => "payment_method", 'short' => false, 'order' => 'ASC'],
        ['text' => "Fecha de pago", 'value' => "payment_date", 'short' => false, 'order' => 'ASC'],
        ['text' => "Monto", 'value' => "payment_amount", 'short' => false, 'order' => 'ASC'],
        ['text' => "Banco", 'value' => "payment_bank", 'short' => false, 'order' => 'ASC'],
        // ['text' => "Activo", 'value' => "is_active", 'short' => false, 'order' => 'ASC'],
        ['text' => "Evento", 'value' => "event", 'short' => false, 'order' => 'ASC'],
        ['text' => "Cliente", 'value' => "customer", 'short' => false, 'order' => 'ASC'],
        ['text' => "Usuario", 'value' => "user", 'short' => false, 'order' => 'ASC'],
        ['text' => "Correo", 'value' => "email", 'short' => true, 'order' => 'ASC'],
    ];


}
