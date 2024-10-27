<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bundle_promotion;
use App\Models\Bundle_promotion_product;

use App\Models\Child_group_product;
use App\Models\Group_product;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Inner_group_product;


//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BundlePromotionController extends Controller
{
    function temp_bundles(Promotion $promotion, Request $request){
        // dd($request->all());
        if(! empty($request->product_ids)){
            foreach($request->product_ids as $key=>$id){
                $data = [
                    'group_id'=>$request->category_id,'inner_group_id'=>$request->sub_category_id,
                    'child_group_id'=>$request->child_category_id,
                    'product_id'=>$id, 'product_combination_id'=>$request->combination_id[$key],
                    'user_id'=>Auth::user()->id
                ];
                $check = Bundle_promotion_product::where(['product_id'=>$id, 'user_id'=>Auth::user()->id]);
                if($check->count() <1){
                    Bundle_promotion_product::create($data);
                }
            }
        }

        $bundle_promotion_products = Bundle_promotion_product::where([
            'user_id'=>Auth::user()->id,  'bundle_promotion_id'=>null
        ])->get();

        return view('common.ad.promotion.bundle.temp-bundles', compact('bundle_promotion_products','promotion'));
    }

    // remove item form temporary_bundle item
    function remove_temp(Bundle_promotion_product $bundle_promotion_product){
        $bundle_promotion_product->delete();
    }

    function create_bundle(Request $request){

        if(empty($request->discount_price) || $request->discount_price==null){
           return response()->json(['warning',"<p class='alert alert-danger'>Please set discount prices for all items as a single order item </p>"]);
        }

        $bundle_promotion_products = Bundle_promotion_product::where([
            'user_id'=>Auth::user()->id,  'bundle_promotion_id'=>null
        ]);

        $regular_price = $discount_price = array();

        foreach($bundle_promotion_products->get() as $item){
            $regular_price[] = $item->product->sale_price;
            // echo 'price: '.$item->product->sale_price.'<br/>';
        }
        // echo '<br/>Total price: '. array_sum($regular_price);

        $bp = Bundle_promotion::create([
            'promotion_id'=>$request->promotion_id,
            'total_items'=>$bundle_promotion_products->count(),
            'regular_price'=> array_sum($regular_price),
            'discount_price'=>$request->discount_price
        ]);
        $this->bunlde_photo($bp);

        $bundle_promotion_products->update([
            'bundle_promotion_id'=>$bp->id
        ]);

        return response()->json(['success',"<p class='alert alert-success'>The bundle has been created successfully</p>"]);
    }

    function bunlde_photo($bundle_promotion,$type=null){

        if (request()->has('bunlde_photo')) {
            $fieldFile = request()->bunlde_photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(500, 500);
            Storage::disk('public')->put("images/promotion/bundle/".$imageName, (string) $image->encode());
            $bundle_promotion->update(['photo'=>"images/promotion/bundle/".$imageName]);

            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }





}
