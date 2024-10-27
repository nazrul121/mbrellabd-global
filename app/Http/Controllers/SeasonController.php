<?php

namespace App\Http\Controllers;

use App\Models\Child_group;
use App\Models\Group;
use App\Models\Inner_group;

use App\Models\Product;
use App\Models\Product_meta;
use App\Models\Product_season;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    function season_products(Request $request, $lang, $slug){
        // $meta_data = Product_meta::inRandomOrder()->take(3)->get();
        $season = Season::where('slug',$slug)->first();
   
        if($season==null) return view('errors.408');
        
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

      
        if($request->category && $request->color && $request->price){
            // dd('cat color price ');
            $variation_option_ids = array(); $cat_ids = array();

            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            foreach(explode(',',$color) as $v){ $variation_option_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id', $variation_option_ids)
            ->Where('product_season.season_id',$season->id)
            ->whereIn('product_season.group_id', $cat_ids)
            ->where('product_season.status','1')
            ->orderBy('product_variation_option.'.$field,$sortBy)
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            // dd($min.' = '.$max);

            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('season-products',compact('season','products','meta_data'));
        }
        else if($request->category && $request->color){
            // dd('cat color in - '.$field);
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_option_ids = array();
            foreach(explode(',',$color) as $v){ $variation_option_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id', $variation_option_ids)
            ->whereIn('product_season.group_id', $cat_ids)
            ->Where('product_season.season_id',$season->id)
            ->where('product_season.status','1')
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            // dd('only price '.$field);
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Product::join('product_season', 'products.id', '=', 'product_season.product_id')
                ->where('product_season.season_id',$season->id)
                ->where('product_season.status','1')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->orderBy('products.'.$field,$sortBy)
                ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            // dd('color only'.$field);
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_option_ids = array();
            foreach(explode(',',$color) as $v){
                // echo 'color: id: '.explode('-',$v)[0].' , name: '.explode('-',$v)[1].'<br/>';
                $variation_option_ids[]['variation_option_id'] = (int)explode('-',$v)[0];
            }
            // dd($variation_ids);
            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_option_ids)
            ->Where('product_season.season_id',$season->id)
            ->where('product_season.status','1')
            ->orderBy('product_variation_option.'.$field,$sortBy)
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){
            // dd('cat only');
            $product_ids = $this->category_product_ids($request, $season);
        }
        else{
            $product_ids = Product_season::where('status','1')->where('season_id',$season->id)->select('product_id')
            ->distinct('product_id')->get()->toArray();
        }

        $products = Product::where('status','1')->whereIn('id',$product_ids)->orderBy($field,$sortBy)->select(['id','title','slug','thumbs','sale_price'])->paginate(20);

        return view('season-products',compact('season','products'));
    }

    function group_products( Request $request, $lang, $seasonSlug, $slug){
        $meta_data = Product_meta::inRandomOrder()->take(3)->get();

    
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $group_id = Group::where('slug',$slug)->pluck('id')->first();
        $season = Season::where('slug',$seasonSlug)->first();
        $group_season_id = DB::table('group_season')->where(['group_id'=>$group_id,'season_id'=>$season->id])->pluck('id')->first();

        if($request->category && $request->color && $request->price){
            // dd('cat color price in inner');
            $variation_option_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_option_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_option_ids)
            ->WhereIn('product_season.inner_group_id',$cat_ids)
            ->Where('product_season.season_id',$season->id)
            ->where('product_season.status','1')
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('season-products',compact('season','products','meta_data'));
        }
        else if($request->category && $request->color){
            // dd('cat color in inner');
            $variation_option_ids = array(); $cat_ids = array();

            foreach(explode(',',$request->color) as $c){$variation_option_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_option_ids)
            ->WhereIn('product_season.inner_group_id',$cat_ids)
            ->Where('product_season.season_id',$season->id)
            ->where('product_season.status','1')
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            // dd('only price');
            $min = trim(explode('-',$request->price)[0]) / session()->get('user_currency')->value;
            $max = trim(explode('-',$request->price)[1]) / session()->get('user_currency')->value;

            $product_ids = Product::join('product_season', 'products.id', '=', 'product_season.product_id')
            ->whereBetween('products.sale_price',array($min, $max) )
            ->Where(['product_season.season_id'=>$season->id,'group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_option_ids = array();
            foreach(explode(',',$color) as $v){ $variation_option_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}
            // dd($variation_ids);
            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_option_ids)
            ->Where(['product_season.season_id'=>$season->id,'group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_variation_option.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){
            $product_ids = $this->category_product_ids($request->category, $season);
        }
        else{
            // dd('else');
            $product_ids = Product_season::where(['season_id'=>$season->id,'group_id'=>$group_id,'status'=>'1'])->select('product_id')->distinct('product_id')->get()->toArray();
        }
        // dd($product_ids);
        $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
        return view('season-products',compact('season','products','meta_data'));
    }

    function inner_group_products(Request $request, $lang,$seasonSlug, $slug){
        $meta_data = Product_meta::inRandomOrder()->take(3)->get();

        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $group_id = Inner_group::where('slug',$slug)->pluck('id')->first();

        $season = Season::where('slug',$seasonSlug)->first();
        $group_season_id = DB::table('inner_group_season')->where(['inner_group_id'=>$group_id,'season_id'=>$season->id])->pluck('id')->first();

        if($request->category && $request->color && $request->price){
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;
            $variation_ids = array(); $cat_ids = array();

            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->whereIn('product_season.child_group_id', $cat_ids)
            ->Where(['product_season.season_id'=>$season->id, 'product_season.inner_group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('season-products',compact('season','products','meta_data'));
        }
        else if($request->category && $request->color){
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;
            $variation_ids = array(); $cat_ids = array();

            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->whereIn('product_season.child_group_id', $cat_ids)
            ->Where(['product_season.season_id'=>$season->id, 'product_season.inner_group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Product::join('product_season', 'products.id', '=', 'product_season.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->Where(['product_season.season_id'=>$season->id,'inner_group_id'=>$group_id])
                ->where('product_season.status','1')
                ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_ids = array();
            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0];}

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->Where('product_season.season_id',$season->id)
            ->Where('product_season.inner_group_id',$group_id)
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){
            $product_ids = $this->category_product_ids($request->category,$season);
        }
        else{
            $product_ids = Product_season::where(['inner_group_id'=>$group_id,'season_id'=>$season->id,'status'=>'1'])->select('product_id')->distinct('product_id')->get()->toArray();
        }

        
        $products = Product::where('status','1')->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
        return view('products',compact('products','meta_data'));
    }

    function child_group_products(Request $request, $lang, $seasonSlug, $slug){
        $meta_data = Product_meta::inRandomOrder()->take(3)->get();

        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $group_id = Child_group::where('slug',$slug)->pluck('id')->first();
        $season = Season::where('slug',$seasonSlug)->first();
        $group_season_id = DB::table('child_group_season')->where(['child_group_id'=>$group_id,'season_id'=>$season->id])->pluck('id')->first();


        if($request->category && $request->color && $request->price){
            // dd('cat color price in child');
            $variation_ids = array();
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }
            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->where(['season_id'=>$season->id,'child_group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('season-products',compact('season','products','meta_data'));
        }
        else if($request->category && $request->color){
            // dd('cat color in child');
            $variation_ids = array();
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }
            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0]; }

            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->where(['season_id'=>$season->id,'child_group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Product::join('product_season', 'products.id', '=', 'product_season.product_id')
            ->whereBetween('products.sale_price',array($min, $max) )
            ->where(['season_id'=>$season->id,'child_group_id'=>$group_id])
            ->where('products.status','1')
            ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_ids = array();
            foreach(explode(',',$color) as $v){$variation_ids[]['variation_option_id'] = (int)explode('-',$v)[0]; }
            $product_ids = Product_season::join('product_variation_option', 'product_season.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->where(['season_id'=>$season->id,'child_group_id'=>$group_id])
            ->where('product_season.status','1')
            ->select('product_season.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){
            $product_ids = $this->category_product_ids($request, $season);
        }
        else{
            $product_ids = Product_season::where('product_season.status','1')
            ->where(['child_group_id'=>$group_id,'season_id'=>$season->id])->select('product_id')->distinct('product_id')->get()->toArray();
        }


        $products = Product::where('status','1')->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
        return view('products',compact('products','meta_data'));
    }

    private function sortBy($request){
        // dd($request->sorting);
        if($request->sorting=='title'){ 
            $sortBy = 'ASC'; 
        }
        else{
            if($request->sorting=='newest'){ $sortBy ='ASC'; }
            else if($request->sorting=='oldest'){ $sortBy ='DESC';  }
            else if($request->sorting=='low-price'){ $sortBy ='ASC'; }
            else if($request->sorting=='high-price'){ $sortBy ='DESC'; }
            else { $sortBy = 'ASC';} return $sortBy;
        }

    }

    private function sortField($request){
        // dd($request->sorting);
        if($request->sorting=='title'){
            $field = 'ASC';
        }else{
            if($request->sorting=='newest'){ $field = 'id'; }
            else if($request->sorting=='oldest'){ $field = 'id'; }
            else if($request->sorting=='low-price'){ $field = 'sale_price'; }
            else if($request->sorting=='high-price'){ $field = 'sale_price'; }
            else { $field = 'id';}
            return $field;
        }
        
    }

    // convert max-price into system default currency
    private function max_price($maxPrice){
        if(strtolower(session()->get('user_currency')->name )=='bdt'){
            return $maxPrice;
        }else{
            return $maxPrice * (session()->get('user_currency')->value);
        }
    }

    private function min_price($minPrice){
        if(strtolower(session()->get('user_currency')->name )=='bdt'){
            return $minPrice;
        }else{
            return $minPrice * (session()->get('user_currency')->value);
        }
    }

    private function category_product_ids($request, $season){
        $group_ids = array();
 

        if( explode('-',$request->category)[0] =='seasonItems'){
            // dd('all');
            foreach(explode(',',$request->category) as $cat){
                $group_ids['group_id'] = explode('-',$cat)[1];
            }
          

            return Product_season::where(['status'=>'1', 'season_id'=>$season->id])->whereIn('group_id',$group_ids)->select('product_id')
            ->distinct('product_id')->get()->toArray();

        }

        if( explode('-',$request->category)[0] =='seasonGroup'){
            foreach(explode(',',$request->category) as $cat){
                $group_ids[]['group_id'] = explode('-',$cat)[1];
            }
            
            return Product_season::where(['status'=>'1', 'season_id'=>$season->id])->whereIn('inner_group_id',$group_ids)->select('product_id')->distinct('product_id')->get()->toArray();
        }

        if(explode('-',$request->category)[0]=='seasonInner'){
            foreach(explode(',',$request->category) as $cat){
                $group_ids[]['inner_group_id'] = explode('-',$cat)[1];
            }
            return Product_season::where(['status'=>'1', 'season_id'=>$season->id])->whereIn('child_group_id',$group_ids)->select('product_id')->distinct('product_id')->get()->toArray();
        }
        if(explode('-',$request->category)[0]=='seasonChild'){
            // dd('child')
            foreach(explode(',',$request->category) as $cat){
                $group_ids[]['child_group_id'] = explode('-',$cat)[1];
            }
            return Product_season::where(['status'=>'1', 'season_id'=>$season->id])->whereIn('child_group_id',$group_ids)->select('product_id')->distinct('product_id')->get()->toArray();
        }

    }


}
