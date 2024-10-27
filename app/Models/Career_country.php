<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career_country extends Model
{
    protected $guarded = [];
    protected $table = 'career_country';


    function career(){
        return $this->belongsTo(Career::class);
    }
    
    function country(){
        return $this->belongsTo(Country::class);
    }
}
