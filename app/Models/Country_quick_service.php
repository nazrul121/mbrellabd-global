<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_quick_service extends Model
{
    protected $guarded = [];
    protected $table = 'country_quick_service';

    function country(){
        return $this->belongsTo(Country::class);
    }

    function quick_service(){
        return $this->belongsTo(Quick_service::class);
    }
}
