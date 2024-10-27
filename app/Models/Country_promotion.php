<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country_promotion extends Model
{
    protected $guarded = [];
    protected $table = 'country_promotion';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function promotion(){
        return $this->belongsTo(Promotion::class);
    }
}
