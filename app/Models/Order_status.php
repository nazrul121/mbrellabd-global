<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_status extends Model
{
    protected $guarded = [];

    function orders(){
        return $this->hasMany(Order::class);
    }
}
