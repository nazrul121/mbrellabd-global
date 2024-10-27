<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // check if category id belongs to category_product
    function cat_belongsTo_product($pro_id, $cat_id){
        $q = Group_product::where([ 'product_id'=>$pro_id, 'group_id'=>$cat_id]);
        if($q->count()>0) return true; else return false;
    }

    function sub_cat_belongsTo_product($pro_id, $cat_id){
        $q = Inner_group_product::where([ 'product_id'=>$pro_id, 'inner_group_id'=>$cat_id ]);
        if($q->count()>0) return true; else return false;
    }

    function child_cat_belongsTo_product($pro_id, $cat_id){
        $q = Child_group_product::where([ 'product_id'=>$pro_id, 'child_group_id'=>$cat_id ]);
        if($q->count()>0) return true; else return false;
    }




    // relationship
    function product_weight(){
        return $this->hasOne(Product_weight::class);
    }

    function brand(){
        return $this->belongsTo(Brand::class);
    }
    function product_photos(){
        return $this->hasMany(Product_photo::class);
    }

    function product_videos(){
        return $this->hasMany(Product_video::class);
    }

    function product_terms(){
        return $this->hasMany(Product_term::class);
    }

    function highlights(){
        return $this->belongsToMany(Highlight::class);
    }


    function users(){
        return $this->belongsToMany(User::class);
    }

    function groups(){
        return $this->belongsToMany(Group::class);
    }
    function inner_groups(){
        return $this->belongsToMany(Inner_group::class);
    }
    function child_groups(){
        return $this->belongsToMany(Child_group::class);
    }


    function product_combinations(){
        return $this->hasMany(Product_combination::class);
    }
    function product_variation_options(){
        return $this->hasMany(Product_variation_option::class);
    }
    function variation_option_photos(){
        return $this->hasMany(Variation_option_photo::class);
    }

    function promotion_summaries(){
        return $this->belongsToMany(Promotion_summary::class);
    }

    function promotions(){
        return $this->belongsToMany(Promotion::class);
    }

    function size_chirt(){
        return $this->belongsTo(Size_chirt::class);
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

    function product_metas(){
        return $this->hasMany(Product_meta::class);
    }

    function order_items(){
        return $this->hasMany(Order_item::class);
    }
}
