<?php

namespace App\Http\Controllers;

use App\Models\Child_group;
use App\Models\Child_group_meta;
use App\Models\Child_group_product;
use App\Models\product_variation_option;
use App\Models\Group;
use App\Models\Group_meta;
use App\Models\Group_product;
use App\Models\Highlight;
use App\Models\Highlight_product;
use App\Models\Inner_group;
use App\Models\Inner_group_meta;
use App\Models\Inner_group_product;
use App\Models\Meta;
use App\Models\Product;
use App\Models\Product_meta;

use App\Models\Variation_option;
use App\Models\Variation_option_photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Session;

class ProductController extends Controller
{
    function sub_categories(Group $group){
        return DB::table('inner_groups')->select('id','title')->where(['group_id'=>$group->id])->get();
    }

    function child_categories(Inner_group $inner_group){
        return DB::table('child_groups')->select('id','title')->where(['inner_group_id'=>$inner_group->id])->get();
    }

    function single_product(Request $request, $lang, $slug){
        $columns = Schema::getColumnListing('product_variants');
        $product = Product::where('status','1')->where('slug',$slug)->first();
        $countryProduct = \DB::table('country_product')->where(['country_id'=>session('user_currency')->id, 'product_id'=>$product->id]);

        if($product !=null && $countryProduct->count()>0){
            $meta_data = Product_meta::where('product_id',$product->id)->get();
            return view('product-details', compact('product','columns','meta_data'));
        }
        else return view('errors.404');
    }


    //single product for ajax request
    function modal_product(Request $request, $lang, Product $product){
        $columns = Schema::getColumnListing('product_variants');
        return view('includes.quickView', compact('product','columns'));
    }

    function all_products(Request $request, $lang){
  
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);
        $product_ids = array();
        $meta_data = Meta::where('pageFor','products')->get();
 

        if($request->category && $request->color && $request->price){
            // dd('cat color price in inner');
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[2]; }

            $product_ids = Inner_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'inner_group_product.product_id')
            ->whereIn('product_variation_option.variation_id',$variation_ids)
            ->whereIn('inner_group_product.inner_group_id',$cat_ids)
            ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products', compact('products', 'meta_data'));
        }
        else if($request->category && $request->color){
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[2]; }

            $product_ids = Inner_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'inner_group_product.product_id')
            ->whereIn('product_variation_option.variation_id',$variation_ids)
            ->whereIn('inner_group_product.inner_group_id',$cat_ids)
            ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            $product_ids = Product::where('status','1')->whereBetween('sale_price',array($min, $max) )->select('id')->get()->toArray();
        }
        else if($request->group){
            $groups = explode(',',$request->group);
            $ids = Group_product::whereIn('group_id',$groups)->select('product_id')->distinct('product_id')->get()->toArray();
            $products = Product::where('status','1')->whereIn('id',$ids)
            ->select(['id','title','slug','thumbs','sale_price'])->orderBy($field, $sortBy)->paginate(20);
        }

        else if($request->inner){
            $groups = explode(',',$request->inner);
            $ids = Inner_group_product::whereIn('inner_group_id',$groups)->select('product_id')->distinct('product_id')->get()->toArray();
            $products = Product::where('status','1')->whereIn('id',$ids)->select(['id','title','slug','thumbs','sale_price'])
            ->orderBy($field, $sortBy)->paginate(20);
        }
        else if($request->color){
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1);
            else $color = $request->color;
            $variation_ids = array();
            foreach(explode(',',$color) as $c){ $variation_ids[]['variation_id'] = $c[0]; }
            $product_ids = product_variation_option::whereIn('variation_id',$variation_ids)->select('product_id')->get()->toArray();
        }
        else if($request->category){
            $product_ids = $this->category_product_ids($request->category);
        }
        else if($request->keyword){
            $search = $request->keyword;
            $ids = \App\Models\Country_product::where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
            $products = Product::select(['id','title','slug','thumbs','sale_price'])
            ->whereIn('id',$ids)
            ->where('status','1')
            // ->where('title', 'like', '%' . $request->keyword . '%')
            
            ->where(function($query) use ($search){
                $query->where('title', 'LIKE', '%'.$search.'%')
                  ->orWhere('tags', 'LIKE', '%'.$search.'%')
                  ->orWhere('description', 'LIKE', '%'.$search.'%')
                  ->orWhere('design_code', 'LIKE', '%'.$search.'%')
                  ->orWhere('design_year', 'LIKE', '%'.$search.'%')
                ;
            })
            // ->whereIn('id',$product_ids)
            ->orderBy($field, $sortBy)->paginate(20);
          
            return view('products', compact('products', 'meta_data'));
        }else{
            $ids = \App\Models\Country_product::where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
            $products = Product::whereIn('id',$ids)->where('status','1')->select(['id','title','slug','thumbs','sale_price'])->orderBy($field, $sortBy)->paginate(20);
            return view('products', compact('products', 'meta_data'));
        }


        $products = Product::where('status','1')->whereIn('id',$product_ids)->select(['id','title','slug','thumbs','sale_price'])->orderBy($field, $sortBy)->paginate(20);
        return view('products', compact('products', 'meta_data'));
    }

    function group_products(Request $request, $lang, $slug){
        $group_id = Group::where('slug',$slug)->pluck('id')->first();

        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        if($request->category && $request->color && $request->price){
            // dd('cat color price in group');
            $variation_option_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_option_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[1]; }

            $product_ids = Inner_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'inner_group_product.product_id')
            // ->where('inner_group_product.group_id',$group_id)
            ->whereIn('product_variation_option.variation_option_id',$variation_option_ids)
            ->whereIn('inner_group_product.inner_group_id',$cat_ids)
            ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            // dd('three in price ');
            $products = Product::where('status','1')->select('id','title','slug','thumbs','sale_price')->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products',compact('products'));
        }
        else if($request->category && $request->price){
            // dd('cat and price in group');
            $cat_ids = array();
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[1]; }

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Product::join('inner_group_product', 'products.id', '=', 'inner_group_product.product_id')
            // ->where('group_product.group_id',$group_id)
            ->whereIn('inner_group_product.inner_group_id',$cat_ids)
            ->whereBetween('products.sale_price',array($min, $max) )
            ->select('product_id')->distinct('product_id')->get()->toArray();

            $products = Product::where('status','1')->select('id','title','slug','thumbs','sale_price')->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products',compact('products'));
        }
        else if($request->color && $request->price){
            // dd('color and price in group');
            $variation_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_id'] = explode('-',$c)[0];}

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'group_product.product_id')
            ->whereIn('product_variation_option.variation_id',$variation_ids)
            ->select('group_product.product_id')->distinct('product_id')->get()->toArray();

            $products = Product::where('status','1')->select('id','title','slug','thumbs','sale_price')->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products',compact('products'));
        }
        else if($request->category && $request->color){
            // dd('cat color in group');
            $variation_option_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_option_ids[]['variation_option_ids'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[1]; }
            // dd($cat_ids);
            $product_ids = Inner_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'inner_group_product.product_id')
            ->whereIn('product_variation_option.variation_option_id',$variation_option_ids)
            ->whereIn('inner_group_product.inner_group_id',$cat_ids)
            ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
           
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
    
            $product_ids = Product::join('group_product', 'products.id', '=', 'group_product.product_id')
            ->where('group_product.group_id',$group_id)
            ->whereBetween('products.sale_price',array($min, $max) )
            ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1);
            else $color = $request->color;

            $product_ids = array();  $variation_option_ids = array();
            foreach(explode(',',$color) as $v){
                // echo 'color: id: '.explode('-',$v)[0].' , name: '.explode('-',$v)[1].'<br/>';
                $variation_option_ids[]['variation_option_ids'] = (int)explode('-',$v)[0];
            }
            // dd($variation_option_ids);
            $product_ids = Group_product::join('product_variation_option', 'group_product.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id', $variation_option_ids)
            ->Where('group_product.group_id',$group_id)
            ->select('group_product.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){
            $product_ids = $this->category_product_ids($request->category);
        }
        else{ 
            $ids = Group_product::where('group_id',$group_id)->select('product_id')->distinct()->get()->toArray();
            $product_ids = \App\Models\Country_product::whereIn('product_id',$ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        }

        $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
        $meta_data = Group_meta::where('group_id',$group_id)->get();
        // dd($meta_data);
        return view('products',compact('products','meta_data'));
    }

    function inner_group_products(Request $request, $lang, $slug){
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $group_id = Inner_group::where('slug',$slug)->pluck('id')->first();
        $product_ids = array();

        if($request->category && $request->color && $request->price){
            // dd('cat color price in inner');
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['inner_group_id'] = explode('-',$c)[1]; }

            $product_ids = Child_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'child_group_product.product_id')
            ->whereIn('product_variation_option.variation_id',$variation_ids)
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products',compact('products'));
        }
        if($request->category && $request->price){
            // dd('cat color price in group');
            $cat_ids = array();
            foreach(explode(',',$request->category) as $c){$cat_ids[]['group_id'] = explode('-',$c)[1]; }

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            $product_ids = Product::join('child_group_product', 'products.id', '=', 'child_group_product.product_id')
            // ->where('group_product.group_id',$group_id)
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            ->whereBetween('products.sale_price',array($min, $max) )
            ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category && $request->color){
            // dd('cat color in inner');
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }
            // dd($variation_ids, $cat_ids);

            $prod_ids = Child_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'child_group_product.product_id')
            ->whereIn('product_variation_option.variation_option_id',$variation_ids)
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();
            $product_ids = \App\Models\Country_product::whereIn('product_id',$prod_ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();

            // dd($variation_ids, $cat_ids, $product_ids);
        }
        else if($request->price){
            // dd('price only in inner category');
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $prod_ids = Product::join('inner_group_product', 'products.id', '=', 'inner_group_product.product_id')
                ->whereBetween('products.sale_price',array($min, $max))
                ->where('inner_group_product.inner_group_id',$group_id)
                ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();

            $product_ids = \App\Models\Country_product::whereIn('product_id',$prod_ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        }
        else if($request->color){
            //if first character is coma (,) remove it
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;
            // dd($color);
            $variation_ids = array();
            foreach(explode(',',$color) as $v){
                // echo 'color: id: '.explode('-',$v)[0].' , name: '.explode('-',$v)[1].'<br/>';
                $variation_ids[]['variation_id'] = (int)explode('-',$v)[0];
            }
            // dd($variation_ids);
            $prod_ids = Inner_group_product::join('product_variation_option', 'inner_group_product.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_option_id',  $variation_ids)
            ->Where('inner_group_product.inner_group_id',$group_id)
            ->select('inner_group_product.product_id')->distinct('product_id')->get()->toArray();
            $product_ids = \App\Models\Country_product::whereIn('product_id',$prod_ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();

        }
        else if($request->category){
            $ids = $this->category_product_ids($request->category);
            $product_ids = \App\Models\Country_product::whereIn('product_id',$ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        }
        else{ 
            $ids = Inner_group_product::where('inner_group_id',$group_id)->select('product_id')->distinct()->get()->toArray();
            $product_ids = \App\Models\Country_product::whereIn('product_id',$ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        }

        $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
       
        $meta_data = Inner_group_meta::where('inner_group_id',$group_id)->get();
        return view('products',compact('products','meta_data'));

    }

    function child_group_products(Request $request, $lang, $slug){
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $group_id = Child_group::where('slug',$slug)->pluck('id')->first();

        if($request->category && $request->color && $request->price){
            // dd('cat color price in child');
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }

            $product_ids = Child_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'child_group_product.product_id')
            ->whereIn('product_variation_option.variation_option_id',$variation_ids)
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            // ->where('child_group_product.child_group_id',$group_id)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );
            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)
            ->whereBetween('sale_price',array($min, $max) )->orderBy($field, $sortBy)->paginate(20);
            return view('products',compact('products'));
        }

        if($request->category && $request->price){
            // dd('cat nad price in child');
            $cat_ids = array();
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }

            $product_ids = Child_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'child_group_product.product_id')
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            // ->where('child_group_product.child_group_id',$group_id)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();

            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $meta_data = Child_group_meta::where('child_group_id',$group_id)->get();

            // dd('three in price ');
            $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])
            ->whereBetween('sale_price',array($min, $max) )
            ->whereIn('id',$product_ids)
            ->orderBy($field, $sortBy)->paginate(20);
            
            return view('products',compact('products','meta_data'));
        }
        else if($request->category && $request->color){
            // dd('cat color in child');
            $variation_ids = array(); $cat_ids = array();
            foreach(explode(',',$request->color) as $c){$variation_ids[]['variation_option_id'] = explode('-',$c)[0];}
            foreach(explode(',',$request->category) as $c){$cat_ids[]['child_group_id'] = explode('-',$c)[1]; }

            // dd($cat_ids);
            $product_ids = Child_group_product::leftJoin('product_variation_option', 'product_variation_option.product_id', '=', 'child_group_product.product_id')
            ->whereIn('product_variation_option.variation_option_id',$variation_ids)
            ->whereIn('child_group_product.child_group_id',$cat_ids)
            ->where('child_group_product.child_group_id',$group_id)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->price){
            // dd('price only');
            $min = $this->min_price( trim(explode('-',$request->price)[0]) );
            $max = $this->max_price( trim(explode('-',$request->price)[1]) );

            $product_ids = Product::join('child_group_product', 'products.id', '=', 'child_group_product.product_id')
                ->whereBetween('products.sale_price',array($min, $max) )
                ->where('child_group_product.child_group_id',$group_id)
                ->select('product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->color){
            // dd('color at child category');
            if($request->color[0]==',') $color = substr($request->color, 1); else $color = $request->color;

            $variation_ids = array();
            foreach(explode(',',$color) as $v){
                // echo 'color: id: '.explode('-',$v)[0].' , name: '.explode('-',$v)[1].'<br/>';
                $variation_ids[]['variation_id'] = (int)explode('-',$v)[0];
            }
            $product_ids = Child_group_product::join('product_variation_option', 'child_group_product.product_id', '=', 'product_variation_option.product_id')
            ->whereIn('product_variation_option.variation_id',  $variation_ids)
            ->Where('child_group_product.child_group_id',$group_id)
            ->select('child_group_product.product_id')->distinct('product_id')->get()->toArray();
        }
        else if($request->category){  $product_ids = $this->category_product_ids($request->category); }

        else{
            $ids = Child_group_product::where('child_group_id',$group_id)->select('product_id')->distinct()->get()->toArray();
            $product_ids = \App\Models\Country_product::whereIn('product_id',$ids)->where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        }

        $products = Product::where('status','1')->select(['id','title','slug','thumbs','sale_price'])->whereIn('id',$product_ids)->orderBy($field, $sortBy)->paginate(20);
        $meta_data = Child_group_meta::where('child_group_id',$group_id)->get();
       
        return view('products',compact('products','meta_data'));
    }

    function highlight_products(Request $request, $lang, Highlight $highlight){
        $sortBy = $this->sortBy($request);
        $field = $this->sortField($request);

        $meta_data = Product_meta::inRandomOrder()->take(3)->get();
 
        $product_ids = Highlight_product::where('highlight_id',$highlight->id)->select('product_id')->distinct('product_id')->get()->toArray();
        $products = Product::where('status','1')->whereIn('id',$product_ids)->select(['id','title','slug','thumbs','sale_price'])->orderBy($field, $sortBy)->paginate(20);
        return view('highlight-products', compact('products', 'meta_data'));

    }

    function change_variant_photo(Variation_option $variation_option, $thumbs_photo, Product $product){
        $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();

        // return 'product_id: '.$product->id.', variation id:'.$listViewVariationId.', option_id: '.$variation_option->id;
        
        $photo = Variation_option_photo::where(['variation_id'=>$listViewVariationId, 'variation_option_id'=>$variation_option->id, 'product_id'=>$product->id])->pluck($thumbs_photo)->first();
        
        if($photo==null) return url($product->feature_photo);
        return url($photo);
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

    private function category_product_ids($category){
        // dd(request()->segment(3));
        $array = array();
        if(request()->segment(2)=='group'){
            
            foreach(explode(',',$category) as $cat){
                // echo $cat.'<br/>';
                $array[]['inner_group_id'] = explode('-',$cat)[1];
            }
        
            $countryProduct_ids = \App\Models\Country_product::where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
            // $inner_group_ids = \App\Models\Country_inner_group::where('country_id',session('user_currency')->id)->select('inner_group_id')->distinct()->get()->toArray();
            // dd($inner_group_ids);
            $products = Inner_group_product::whereIn('inner_group_id',$array)->whereIn('product_id',$countryProduct_ids)->select('product_id')->distinct()->get()->toArray();
            // dd($products);
            return $products;
        }
        if(request()->segment(2)=='group-in'){
            foreach(explode(',',$category) as $cat){
                $array[]['child_group_id'] = explode('-',$cat)[1];
            }
            return Child_group_product::where('child_group_id',$array)->select('product_id')->distinct('product_id')->get()->toArray();
        }
        if(request()->segment(2)=='child-in'){
            $child = Child_group::where('id',explode('-',$category)[1])->first();
            // dd($child);
             foreach(explode(',',$category) as $cat){
                $array[]['child_group_id'] = explode('-',$cat)[1];
            }
            return Child_group_product::whereIn('child_group_id',$array)->select('product_id')->distinct('product_id')->get()->toArray();
        }

        if(explode('-',$category)[0]=='top'){
            $array = array();
            foreach(explode(',',$category) as $cat){
                $array[]['inner_group_id'] = explode('-',$cat)[1];
            }
            return Inner_group_product::whereIn('inner_group_id',$array)->select('product_id')->distinct('product_id')->get()->toArray();
        }

    }


    function get_option2Variation(Variation_option $variation_option){
        return $variation_option;
    }

    // convert max-price into system default currency
    private function max_price($maxPrice){
        if(strtolower(Session::get('user_currency')->currency_code )=='bdt'){
            return $maxPrice;
        }else{
            return $maxPrice * (Session::get('user_currency')->currencyValue);
        }
    }

    private function min_price($minPrice){
        if(strtolower(Session::get('user_currency')->currency_code )=='bdt'){
            return $minPrice;
        }else{
            return $minPrice * (Session::get('user_currency')->currencyValue);
        }
    }


}
