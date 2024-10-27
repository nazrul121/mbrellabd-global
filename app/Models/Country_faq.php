<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_faq extends Model
{
    protected $guarded = [];
    protected $table = 'country_faq';


    function faq(){
        return $this->belongsTo(Faq::class);
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
}
