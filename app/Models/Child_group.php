<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child_group extends Model
{
    protected $guarded = [];


    function belong_to_session($group_id, $season_id){
        return Child_group_season::where(['child_group_id'=>$group_id,'season_id'=>$season_id])->count();
    }


    // relationship
    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }

    function products(){
        return $this->belongsToMany(Product::class);
    }

    function promotions(){
        return $this->belongsToMany(Promotion::class);
    }

    function product_promotion_summaries(){
        return $this->belongsToMany(Product_promotion_summary::class);
    }

    function zones(){
        return $this->belongsToMany(Zone::class);
    }

    function seasons(){
        return $this->belongsToMany(Season::class);
    }
    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
