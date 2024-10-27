<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country_video extends Model
{
    protected $guarded = [];
    protected $table = 'country_video';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function video(){
        return $this->belongsTo(Video::class);
    }
}
