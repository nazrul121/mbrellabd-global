<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_discount extends Model
{
    protected $guarded = [];

    // relationship
    function user(){
        return $this->belongsTo(User::class);
    }

    function invoice_discount_orders(){
        return $this->hasMany(Invoice_discount_order::class);
    }

    function orders(){
        return $this->belongsToMany(Order::class);
    }

    function countries(){
        return $this->belongsToMany(Country::class);
    }


}
