<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quick_service extends Model
{
    protected $guarded = [];

    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
