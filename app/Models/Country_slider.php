<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country_slider extends Model
{
    protected $guarded = [];
    protected $table = 'country_slider';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function slider(){
        return $this->belongsTo(Slider::class);
    }
}
