<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_invoice_discount extends Model
{
    protected $guarded = [];
    protected $table = 'country_invoice_discount';


    function invoice_discount(){
        return $this->belongsTo(Invoice_discount::class);
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
    
}
