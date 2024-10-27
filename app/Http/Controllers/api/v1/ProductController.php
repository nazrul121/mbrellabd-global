<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Group_product;
use App\Models\Inner_group_product;
use App\Models\Child_group_product;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Child_group;

class ProductController extends Controller
{
    public function groups($countryId){
        $ids = \App\Models\Country_group::where('country_id',$countryId)->select('group_id')->distinct()->get()->toArray();
        $groups = Group::where(['status'=>'1'])->whereIn('id',$ids)
            ->with(['inner_groups' => function($query) {
                $query->where(['status'=>'1'])->select('id', 'title','photo','group_id')
                ->with(['child_groups' => function($query) {
                    $query->where(['status'=>'1'])->select('id', 'title','photo','inner_group_id');
                }]);
            }])
            ->withCount('products')
            ->select(['id', 'title', 'photo'])
            ->get();
        return response()->json($groups);
    }


    public function inner_groups($groupId, $countryId){
        $ids = \App\Models\Country_inner_group::where('country_id',$countryId)->select('inner_group_id')->distinct()->get()->toArray();
        $groups = Inner_group::where(['group_id'=>$groupId ,'status'=>'1'])
            ->whereIn('id',$ids)
            ->select('id', 'title', 'photo')
            ->withCount('products')
            ->orderBy('sort_by')->get();
        return response()->json($groups);
    }

    public function child_groups($innerGroupId, $countryId){
        $ids = \App\Models\Child_group_country::where('country_id',$countryId)->select('child_group_id')->distinct()->get()->toArray();
        $groups = Child_group::where(['inner_group_id'=>$innerGroupId ,'status'=>'1'])
            ->whereIn('id',$ids)
            ->select('id', 'title', 'photo')
            ->withCount('products')
            ->orderBy('sort_by')->get();
        return response()->json($groups);
    }

    function top_categories($countryId){
        $ids = \App\Models\Country_inner_group::where('country_id',$countryId)->select('inner_group_id')->distinct()->get()->toArray();
        $groups = Inner_group::where(['is_top'=>'1' ,'status'=>'1'])
        ->whereIn('id',$ids)
        ->select('id', 'title', 'photo')
        ->withCount('products')
        ->orderBy('sort_by')->get();
        return response()->json($groups);
    }



    public function all_products($countryId,$number=20){
        $ids = \App\Models\Country_product::where('country_id',$countryId)->select('product_id')->distinct()->get()->toArray();

        $products = Product::with('size_chirt:id,title,photo')->whereIn('id',$ids)
            ->select('id','title','thumbs')
            ->where('status','1')->paginate($number);

        return response()->json( $products );
    }
    public function group_products($groupId, $countryId, $number=20){
        $ids = \App\Models\Country_group::where('country_id',$countryId)->select('group_id')->distinct()->get()->toArray();

        $product_ids = Group_product::whereIn('group_id',$ids)->where('group_id',$groupId)->select('product_id')->distinct()->get()->toArray();
        $products = Product::with('size_chirt:id,title,photo')
            ->select('id','title','thumbs')
            ->whereIn('id',$product_ids)
            ->where('status','1')->paginate($number);
        return response()->json($products);
    }


    public function inner_group_products($groupId, $countryId, $number=20){
        $ids = \App\Models\Country_inner_group::where('country_id',$countryId)->select('inner_group_id')->distinct()->get()->toArray();

        $product_ids = Inner_group_product::whereIn('id',$ids)->where('inner_group_id',$groupId)->select('product_id')->distinct()->get()->toArray();
        $products = Product::with('size_chirt:id,title,photo')
            ->select('id','title','thumbs')
            ->whereIn('id',$product_ids)
            ->where('status','1')->paginate($number);
        return response()->json( $products);
    }

    public function child_group_products($groupId, $countryId, $number=20){
        $product_ids = Child_group_product::where('child_group_id',$groupId)->select('product_id')->distinct()->get()->toArray();
        $products = Product::with('size_chirt:id,title,photo')
            ->select('id','title','thumbs')
            ->whereIn('id',$product_ids)
            ->where('status','1')->paginate($number);
        return response()->json( $products );
    }

    public function single_product($productId){
        $product = Product::with('size_chirt:id,title,photo')
            ->with('product_photos:id,photo')
            ->with('product_videos')
            ->with('product_terms')
            ->with('groups:id,title,photo')
            ->with('inner_groups:id,title,photo')
            ->with('child_groups:id,title,photo')
            
            ->where('id',$productId)->first();
        return response()->json( $product );
    }




}
