<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight_product extends Model
{
    protected $guarded = [];
    protected $table = 'highlight_product';

    function product(){
        return $this->belongsTo(Product::class);
    }
    function highlight(){
        return $this->belongsTo(Highlight::class);
    }
}
