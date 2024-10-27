<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_gateway extends Model
{
    protected $guarded = [];

    // relationship

    function orders(){
        return $this->hasMany(Order::class);
    }
}
