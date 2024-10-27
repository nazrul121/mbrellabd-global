<?php

namespace App\Http\Controllers;

use App\Models\Product_promotion;
use App\Models\Product_variation_option;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Session;

class PromoProductController extends Controller
{
    function promotions(Request $request, $lang){
        $promotions = Promotion::where('status','1')->get();
        return view('promotions',compact('promotions'));
    }

    public function index(Request $request, $lang, $slug){
        // dd($slug);
        $promotion= Promotion::where('slug',$slug)->select('id','promotion_type_id','title','slug','end_date','end_time','description')->first();

        if($promotion !=null){
            if($promotion->promotion_type->name_origin=='bundle-promotion'){
                $bundle_promotions =  \App\models\Bundle_promotion::where('promotion_id',$promotion->id)->paginate(24);
                return view('bundle-promotions',compact('promotion','bundle_promotions'));
            }
    
            $sortBy = $this->sortBy($request);
            $field = $this->sortField($request);
    
            if($request->category && $request->color && $request->price){
                // dd('cat color price in promotion');
                $min = $this->min_price( trim(explode('-',$request->price)[0]) );
                $max = $this->max_price( trim(explode('-',$request->price)[1]) );
    
                $option_ids = $group_ids = array();
                foreach(explode(',',$request->color) as $c){$option_ids[]['variation_option_id'] = explode('-',$c)[0];};
                foreach(explode(',',$request->category) as $c){$group_ids[]['group_id'] = explode('-',$c)[0];};
    
                $product_ids = Product_variation_option::leftJoin('product_promotion','product_promotion.product_id','=','product_variation_option.product_id')
                ->leftJoin('group_product','group_product.product_id','=','product_variation_option.product_id')
                ->leftJoin('products','products.id','=','product_variation_option.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->where('product_promotion.promotion_id',$promotion->id)
                ->whereIn('group_product.group_id',$group_ids)
                ->whereIn('product_variation_option.variation_option_id',$option_ids)->select('product_promotion.product_id')->get()->toArray();
    
            }
    
            else if($request->category && $request->price){
                // dd('category + price in promotion');
                $min = $this->min_price( trim(explode('-',$request->price)[0]) );
                $max = $this->max_price( trim(explode('-',$request->price)[1]) );
    
                $option_ids = $group_ids = array();
                foreach(explode(',',$request->category) as $c){$group_ids[]['group_id'] = explode('-',$c)[0];};
    
                $product_ids = Product_promotion::leftJoin('group_product','group_product.product_id','=','product_promotion.product_id')
                ->leftJoin('products','products.id','=','product_promotion.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
    
                ->whereIn('product_promotion.group_id',$group_ids)->select('product_promotion.product_id')->get()->toArray();
            }
            else if($request->color && $request->price){
                // dd('color and price in promotion');
                $min = $this->min_price( trim(explode('-',$request->price)[0]) );
                $max = $this->max_price( trim(explode('-',$request->price)[1]) );
    
                $option_ids = array();
                foreach(explode(',',$request->color) as $c){$option_ids[]['variation_option_id'] = explode('-',$c)[0];};
    
                $product_ids = Product_variation_option::leftJoin('product_promotion','product_promotion.product_id','=','product_variation_option.product_id')
                ->leftJoin('group_product','group_product.product_id','=','product_variation_option.product_id')
                ->leftJoin('products','products.id','=','product_variation_option.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->where('product_promotion.promotion_id',$promotion->id)
                ->whereIn('product_variation_option.variation_option_id',$option_ids)->select('product_promotion.product_id')->get()->toArray();
    
            }
            else if($request->category && $request->color){
                // dd('cat and color in promotion');
                $option_ids = $group_ids = array();
                foreach(explode(',',$request->color) as $c){$option_ids[]['variation_option_id'] = explode('-',$c)[0];};
                foreach(explode(',',$request->category) as $c){$group_ids[]['group_id'] = explode('-',$c)[0];};
    
                $product_ids = Product_variation_option::leftJoin('product_promotion','product_promotion.product_id','=','product_variation_option.product_id')
                ->leftJoin('group_product','group_product.product_id','=','product_variation_option.product_id')
                ->where('product_promotion.promotion_id',$promotion->id)
                ->whereIn('group_product.group_id',$group_ids)
                ->whereIn('product_variation_option.variation_option_id',$option_ids)->select('product_promotion.product_id')->get()->toArray();
            }
            else if($request->price){
                // dd('only price in promotion');
                $min = $this->min_price( trim(explode('-',$request->price)[0]) );
                $max = $this->max_price( trim(explode('-',$request->price)[1]) );
    
                $product_ids = Product_promotion::join('products', 'products.id', '=', 'product_promotion.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->where('promotion_id',$promotion->id)
                ->select('product_id')->distinct('product_id')->get()->toArray();
            }
            else if($request->color){
               //if first character is coma (,) remove it
               if($request->color[0]==',') $color = substr($request->color, 1);
               else $color = $request->color;
    
               $variation_ids = $option_ids = array();
               foreach(explode(',',$color) as $c){ $option_ids[]['variation_option_id'] = $c[0]; }
    
               $product_ids = Product_variation_option::leftJoin('product_promotion','product_promotion.product_id','=','product_variation_option.product_id')
               ->where('product_promotion.promotion_id',$promotion->id)
               ->whereIn('product_variation_option.variation_option_id',$option_ids)->select('product_promotion.product_id')->get()->toArray();
    
            }
            else if($request->category){
                // dd('only category in promotion');
                foreach(explode(',',$request->category) as $c){
                    // echo 'cat: id: '.explode('-',$c)[0].' , name: '.explode('-',$c)[1].'<br/>';
                    $group_ids[]['group_id'] = (int)explode('-',$c)[0];
                }
                $product_ids = \App\Models\Product_promotion::whereIn('group_id',$group_ids)->where('promotion_id',$promotion->id)->select('product_id')->get()->toArray();
            }
            else{
                $product_ids = \App\Models\Product_promotion::where('promotion_id',$promotion->id)->select('product_id')->orderBy('id','DESC')->get()->toArray();
                // dd($product_ids);
            }
            // dd($summary_ids);
    
            $products =  \App\Models\Product::where('status','1')->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
            return view('promotion-products',compact('promotion','products'));
        }else{
            return view('errors.408');
        }
    }



    private function sortBy($request){
        if($request->sorting=='title'){ $sortBy = 'ASC';  }
        else if($request->sorting=='title-desc'){$sortBy ='DESC';}
        else if($request->sorting=='newest'){ $sortBy ='DESC'; }
        else if($request->sorting=='oldest'){ $sortBy ='ASC';  }
        else if($request->sorting=='low-price'){ $sortBy ='ASC'; }
        else if($request->sorting=='high-price'){ $sortBy ='DESC'; }
        else { $sortBy = 'DESC';} return $sortBy;
    }

    private function sortField($request){
        if($request->sorting=='title'){ $field = 'title'; }
        else if($request->sorting=='title-desc'){ $field = 'title';}
        else if($request->sorting=='newest'){ $field = 'id'; }
        else if($request->sorting=='oldest'){ $field = 'id'; }
        else if($request->sorting=='low-price'){ $field = 'sale_price'; }
        else if($request->sorting=='high-price'){ $field = 'sale_price'; }
        else { $field = 'id';}
        return $field;
    }

    // convert max-price into system default currency
    private function max_price($maxPrice){
        if(strtolower(Session::get('user_currency')->name )=='bdt'){
            return $maxPrice;
        }else{
            return $maxPrice * (Session::get('user_currency')->value);
        }
    }

    private function min_price($minPrice){
        if(strtolower(Session::get('user_currency')->name )=='bdt'){
            return $minPrice;
        }else{
            return $minPrice * (Session::get('user_currency')->value);
        }
    }

}
