<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Child_group_product;
use App\Models\Group_product;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Inner_group_product;
use App\Models\Product_promotion;
use App\Models\Product_combination;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Child_group;
use App\Models\order_item;

class ProductPromotionController extends Controller
{
    
    function promotion_form(Promotion $promotion, Request $request){
        // dd($promotion->promotion_type->name_origin);
        if($promotion->promotion_type->name_origin=='group-discount'){
            // $file ='common.ad.promotion.flat.form';
            return view('common.ad.promotion.flat.form', compact('promotion'));
        }
        else if($promotion->promotion_type->name_origin=='bundle-promotion'){
            // $file ='common.ad.promotion.bundle.form';
            return view('common.ad.promotion.bundle.form', compact('promotion'));
        }
        else{
            dd('No promotion defined against this promotion type');
        }
    }

    function show_promotion_form (Promotion $promotion, Request $request){
        // dd($request->all());
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();

        if($request->child_category_id){
            $groupIds =\DB::table('child_group_country')->whereIn('country_id',$promotionCountry)
            ->where('child_group_id',$request->child_category_id)->select('child_group_id')->distinct()->pluck('child_group_id')->toArray();

            $product_ids = Child_group_product::where([
                'group_id'=>$request->category_id,
                'inner_group_id'=>$request->sub_category_id,
                'child_group_id'=>$request->child_category_id
            ])->select('product_id')->distinct('product_id')->get()->toArray();

            $filterProduct_ids = \DB::table('country_product')
                ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
                ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();

            $products = Product::whereIn('id',$filterProduct_ids)->select('id','title','design_code','thumbs')->get();

            return view('common.ad.promotion.flat.form2', compact('promotion','products'));
        }

        if($request->sub_category_id){
            $groupIds =\DB::table('country_inner_group')->whereIn('country_id',$promotionCountry)
                ->where('inner_group_id',$request->sub_category_id)->select('inner_group_id')->distinct()->pluck('inner_group_id')->toArray();
     
            $product_ids = Inner_group_product::whereIn('inner_group_id',$groupIds)->select('product_id')->distinct('product_id')->get()->toArray();
            $filterProduct_ids = \DB::table('country_product')
                ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
                ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();
    
            $products = Product::whereIn('id',$filterProduct_ids)->select('id','title','design_code','thumbs')->get();
            // dd( $groupIds , $product_ids);
            return view('common.ad.promotion.flat.form2', compact('promotion','products'));
        }

        if($request->category_id){
            $groupIds =\DB::table('country_group')->whereIn('country_id',$promotionCountry)
            ->where('group_id',$request->category_id)->select('group_id')->distinct()->pluck('group_id')->toArray();
        
            $product_ids = Group_product::whereIn('group_id',$groupIds)->select('product_id')->distinct('product_id')->get()->toArray();
            $filterProduct_ids = \DB::table('country_product')
                ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
                ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();

            $products = Product::whereIn('id',$filterProduct_ids)->select('id','title','design_code','thumbs')->get();
            // dd($groupIds);
            return view('common.ad.promotion.flat.form2', compact('promotion','products'));
        }
        
    }

    function save_promotion(Promotion $promotion, Request $request){
        $total = 0;
        if(!$request->product_ids){
            return response()->json(['warning' => 'No product item is selected for the promotion!']);
        }else{
            if($request->amount <1){
                return response()->json(['warning' => 'Discount percentage/Amount is not valid!']);
            }
            foreach($request->product_ids as $pid){
                $product = Product::where('id',$pid)->first();
                if($request->discount_in=='percent'){
                    $total =  $product->sale_price - ($product->sale_price * ($request->amount  / 100));
                }else $total =  $product->sale_price - $request->amount;

                $data = ['product_id'=>$pid,'promotion_id'=>$promotion->id];
                $check = Product_promotion::where($data)->where('status','1');
                if($check->count()<1){
                    $data['group_id']=$request->category_id;
                    $data['inner_group_id']=$request->sub_category_id;
                    $data['child_group_id'] = $request->child_group_id;
                    $data['discount_in']=$request->discount_in;
                    $data['discount_value']=$request->amount;
                    $data['discount_price']=$total;
                    $data['net_price']=$product->net_price;
                    $data['sale_price']=$product->sale_price;
                    $data['vat_type']=$product->vat_type;
                    $data['vat']=$product->vat;
                    // dd($data);
                    Product_promotion::create($data);
                }
            }
            return response()->json(['success' => 'Product items has been assinged into the promotion!']);
        }

    }


    private function flatFields($id=null){
        $validator = Validator::make(request()->all(), [
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'child_category_id'=>'required',
            'discount_in'=>'required',
            'amount'=>'required',   'status'=>'required',
        ]); return $validator;
    }

    function destroy(Product_promotion $product_promotion){
        try {
            $product_promotion->delete();
            return response()->json(['success' => 'promotion item hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }


    function promotion_proudcts(Promotion $promotion, Request $request){
 
        if($promotion->promotion_type->name_origin=='group-discount'){
            if($request->draw){
                if($request->search['value']){
                    return $this->flat_promotion_product_search($promotion, $request->search['value']);
                }else return $this->flat_promotion_products($promotion);
            }

            return view('common.ad.promotion.flat.products', compact('promotion'));
        }
        else if($promotion->promotion_type->name_origin=='selected-items'){
            dd('Dev Note: this <b>selected-items</b> promotions are checked at from. Cause, this is pure laravel');
        }
    }



    function get_group_products(Group $group, Promotion $promotion, $checkUncheck='',$is_variation=null){
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
       
        $groupIds =\DB::table('country_group')->whereIn('country_id',$promotionCountry)
            ->where('group_id',$group->id)->select('group_id')->distinct()->pluck('group_id')->toArray();
      
        $product_ids = Group_product::whereIn('group_id',$groupIds)->select('product_id')->distinct('product_id')->get()->toArray();
        $filterProduct_ids = \DB::table('country_product')
            ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
            ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();

        $products = Product::whereIn('id',$filterProduct_ids)->select('id','title','design_code','thumbs')->get();
        return view('common.ad.promotion.flat.show-products',compact('products','promotion','checkUncheck','is_variation'));
    }

    function get_inner_group_products(Request $request, $innerId, Promotion $promotion, $checkUncheck=null,$is_variation=null){
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $groupIds =\DB::table('country_inner_group')->whereIn('country_id',$promotionCountry)
            ->where('inner_group_id',$innerId)->select('inner_group_id')->distinct()->pluck('inner_group_id')->toArray();
 
        $product_ids = Inner_group_product::whereIn('inner_group_id',$groupIds)->select('product_id')->distinct('product_id')->get()->toArray();
        $filterProduct_ids = \DB::table('country_product')
            ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
            ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();

        $products = Product::whereIn('id',$product_ids)->select('id','title','design_code','thumbs')->get();
        return view('common.ad.promotion.flat.show-products',compact('products','promotion','checkUncheck','is_variation'));
    }

    function get_child_group_products(Request $request, $chld_id, Promotion $promotion, $checkUncheck=null,$is_variation=null){
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $groupIds =\DB::table('child_group_country')->whereIn('country_id',$promotionCountry)
            ->where('child_group_id',$chld_id)->select('child_group_id')->distinct()->pluck('child_group_id')->toArray();

        $product_ids = Child_group_product::where([
            'group_id'=>$request->group_id,
            'inner_group_id'=>$request->inner_group_id,
            'child_group_id'=>$chld_id
        ])->select('product_id')->distinct('product_id')->get()->toArray();

        $filterProduct_ids = \DB::table('country_product')
            ->whereIn('country_id', $promotionCountry)->whereIn('product_id', $product_ids)
            ->select('product_id')->distinct()->get()->pluck('product_id')->toArray();

        $products = Product::whereIn('id',$product_ids)->select('id','title','design_code','thumbs')->get();
        return view('common.ad.promotion.flat.show-products',compact('products','promotion','checkUncheck','is_variation'));
    }


    private function flat_promotion_products($promotion){
        $datatable = datatables()::of( Product_promotion::where('promotion_id',$promotion->id) );

        return $this->get_flat_products($datatable);
    }

    private function flat_promotion_product_search($promotion, $keyword){

        $product_ids = Product::where('title','LIKE',"%{$keyword}%")->select('id')->distinct('id')->get()->toArray();
        $datatable = datatables()::of( Product_promotion::where('promotion_id',$promotion->id)->whereIn('product_id',$product_ids)->get() );

        return $this->get_flat_products($datatable);


    }

    private function get_flat_products($datatable){
        return $datatable
        ->editColumn('modify', function ($row) {
            $data = ' <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
            if(check_access('delete-product-from-promotion')){
                $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
            }
            return $data.'</div>';
        })
        ->editColumn('photo', function($row){
            return '<label for="item'.$row->id.'">
            <input type="checkbox" class="promoItem" style="width:25px;height:25px" id="item'.$row->id.'" name="ids[]" value="'.$row->id.'"> 
            <img style="margin-top:-13px;height:30px" src="'.$row->product->thumbs.'"></label>';
        })
        ->editColumn('title', function($row){
            return $row->product->title;
        })
	    ->editColumn('design_code', function($row){
            return $row->product->design_code;
        })
        
        ->editColumn('qty', function($row){
            $qty = array();
            $productCombinations = Product_combination::where('product_id',$row->product_id)->get();
            foreach($productCombinations as $comb){ $qty[] = $comb->qty;}
            if(array_sum($qty) >0) return array_sum($qty);
            else return $row->qty;
        })
        ->rawColumns(['photo','title','qty','modify'])->make(true);
    }


    function remove_product_promotion(Product_promotion $product_promotion){
       
        try {
            $checkOrder = Order_item::where(['product_id'=>$product_promotion->product_id, 'promotion_id'=>$product_promotion->promotion_id]);
            if($checkOrder->count() < 1){
                $product_promotion->delete();
                return response()->json(['success' => 'promotion item hasn been deleted successfully!']);
            }else{
                return response()->json(['alert' => 'promotion item belongs to one/more orders!']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function remove_products_promotion( $ids ){
        $i = 0; $existOrder = 0;
        try {
            foreach(explode(',',$ids) as $id){
                $product_promotion = Product_promotion::find($id);

                $checkOrder = Order_item::where(['product_id'=>$product_promotion->product_id, 'promotion_id'=>$product_promotion->promotion_id]);
                if($checkOrder->count() < 1){
                    $product_promotion->delete();
                    $i++;
                }else{
                    $existOrder++;
                }            
            }
            // $product_promotion->delete();
            return response()->json(['success' => '<b>'.$i.'</b> items hasn been deleted successfully and <b class="text-danger">'. $existOrder.'</b> unable to delete']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
