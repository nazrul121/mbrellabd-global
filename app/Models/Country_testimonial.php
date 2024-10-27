<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_testimonial extends Model
{
    protected $guarded = [];
    protected $table = 'country_testimonial';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function testimonial(){
        return $this->belongsTo(Testimonial::class);
    }
}
