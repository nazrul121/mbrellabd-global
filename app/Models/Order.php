<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }
    
    // relationship
    function country(){
        return $this->belongsTo(Country::class);
    }

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function zone(){
        return $this->belongsTo(Zone::class);
    }

    function shipping_address(){
        return $this->belongsTo(Shipping_address::class);
    }

    function payment_geteway(){
        return $this->belongsTo(Payment_gateway::class);
    }

    function order_status(){
        return $this->belongsTo(Order_status::class);
    }

    function order_items(){
        return $this->hasMany(Order_item::class);
    }

    function order_payments(){
        return $this->hasMany(Order_payment::class);
    }

    function dhl_shipment(){
        return $this->hasOne(Dhl_shipment::class);
    }

    function invoice_discounts(){
        return $this->belongsToMany(Invoice_discount::class);
    }
}
