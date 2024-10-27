<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_User extends Model
{
    protected $table = 'product_user';
    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
