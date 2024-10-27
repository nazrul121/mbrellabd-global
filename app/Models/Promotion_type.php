<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promotion_type extends Model
{
    protected $guarded = [];

    // relationship
    function promotions(){
        return $this->belongsTo(Promotion::class);
    }
}
