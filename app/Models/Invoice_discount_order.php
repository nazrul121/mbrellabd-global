<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_discount_order extends Model
{
    protected $guarded = [];
    protected $table = 'invoice_discount_order';


    // relashionships

    function invoice_discount(){
        return $this->belongsTo(Invoice_discount::class);
    }

    function order(){
        return $this->belongsTo(Order::class);
    }

}
