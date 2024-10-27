<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Add_to_cart_log extends Model
{
    protected $guarded = [];

    function user(){
        return $this->belongsTo(User::class);
    }


}
