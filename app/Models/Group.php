<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = [];

    // count child categories
    function get_child_category_number($group_id){
        $innerGroups = Inner_group::select('id')->where('group_id',$group_id)->get()->toArray();
        return Child_group::whereIn('inner_group_id',$innerGroups)->count();
    }

    function belong_to_session($group_id, $season_id){
       return Group_season::where(['group_id'=>$group_id,'season_id'=>$season_id])->count();
    }



    // relationship
    function inner_groups(){
        return $this->hasMany(Inner_group::class);
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

    function group_products(){
        return $this->hasMany(Group_product::class);
    }
    function inner_group_products(){
        return $this->hasMany(Inner_group_product::class);
    }
    function child_group_products(){
        return $this->hasMany(Child_group_product::class);
    }

    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
