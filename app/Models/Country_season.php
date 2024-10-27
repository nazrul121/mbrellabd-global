<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_season extends Model
{
    protected $guarded = [];
    protected $table = 'country_season';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function season(){
        return $this->belongsTo(Season::class);
    }

}
