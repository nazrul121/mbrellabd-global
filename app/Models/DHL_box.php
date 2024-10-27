<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHL_box extends Model
{
    protected $table = 'dhl_boxes';
    protected $guarded = [];


    // relationship 
    function group(){
        return $this->belongsTo(Group::class);
    }

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }

}
