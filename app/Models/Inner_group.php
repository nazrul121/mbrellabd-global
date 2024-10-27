<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inner_group extends Model
{
    protected $guarded = [];

    function belong_to_session($group_id, $season_id){
        return Inner_group_season::where(['inner_group_id'=>$group_id,'season_id'=>$season_id])->count();
    }


    public function scopeStatus($query){
         return $query->where('status', '=', 1);
    }
    // relationship
    function group(){
        return $this->belongsTo(Group::class);
    }

    function child_groups(){
        return $this->hasMany(Child_group::class);
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

    function seasons(){
        return $this->belongsToMany(Season::class);
    }
    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
