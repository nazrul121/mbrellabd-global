<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_group extends Model
{
    protected $guarded = [];
    protected $table = 'country_group';


    function gorup(){
        return $this->belongsTo(Group::class);
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
}
