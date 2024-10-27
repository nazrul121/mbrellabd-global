<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_inner_group extends Model
{
    protected $guarded = [];
    protected $table = 'country_inner_group';

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }
    
    function country(){
        return $this->belongsTo(Country::class);
    }
}
