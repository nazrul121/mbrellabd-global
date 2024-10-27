<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    public function countries(){
        return $this->belongsToMany(Country::class);
    }

    
}
