<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Product_User;
use App\Models\Product_variation_option;
use App\Models\Variation;
use App\Models\Variation_option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class ProductVariantController extends Controller{

    function create(Request $request, Product $product){
        //add product into hightlight_product table
        if($request->draw){
            return datatables()::of(Product_combination::where('product_id',$product->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '';
                if(check_access('delete-product-variation')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-product-variation')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                return $data;
            })
            ->editColumn('qty', function ($row) {
                if(check_access('update-product-combination-qty')){
                    return '<input type="number" value="'.$row->qty.'" class="form-control p-1 vQty'.$row->id.'" style="width:70px;float:left">
                    <button class="btn btn-sm btn-info editQty" id="'.$row->id.'" > <b class="fa fa-edit"></b></button> ';
                }else return $row->qty;
            })

            ->editColumn('varient_info', function($row) use ($product){
                $string = '';
                foreach(explode('~',$row->combination_string) as $origin){
                    $v = Variation_option::where('origin',$origin)->select('variation_id','title')->first();
                    if($v==null) $string .= '';
                    else $string .= '<span class="badge badge-info" style="font-size:100%;"><b>'.$v->variation->title.'</b>: '.$v->title.'</span> ';
                }
                return $string;
            })
            ->rawColumns(['varient_info','qty','modify'])->make(true);
        }

        $variations = Variation::all();
        return view('common.product.add-variants.index',compact('product','variations'));
    }


    function store(Request $request, Product $product){
        $string = ''; $readable = array(); $variation_origins = array();
        foreach($request->option_ids as $id){
            $string .= Variation_option::where('id',$id)->pluck('origin')->first();
            $readable[] = Variation_option::where('id',$id)->pluck('origin')->first();

            $vid = Variation_option::where('id',$id)->pluck('variation_id')->first();
            $variation_origins[] = Variation::where('id',$vid)->pluck('origin')->first();
        }
        $stringParts = str_split($string);
        sort($stringParts);
        // return response()->json(['success' => implode('~',$variation_origins).' = '.implode('~',$readable).' = '.implode($stringParts)]);
        // dd(Session::get('allOptions'));

        foreach(Session::get('allOptions') as $field=>$option){
            $variation = Variation_option::find($option);
            // dd($variation);
            $data = ['product_id'=>$product->id,'variation_id'=>$variation->variation_id,'variation_option_id'=> $option];
            $check = Product_variation_option::where($data);
            if($check->count() <1){ Product_variation_option::create($data);}
        }

        $check = Product_combination::where(['product_id'=>$product->id, 'unique_string'=>implode($stringParts)])
        ->orWhere('barcode',$request->barcode);

        if($check->count()<1){
            Product_combination::create([
                'product_id'=>$product->id, 'sku'=>rand(),
                'combine_variation'=>implode('~',$variation_origins),
                'combination_string'=>implode('~',$readable),
                'unique_string'=>implode($stringParts),
                'qty'=>$request->qty, 'barcode'=>$request->barcode
            ]);
            //update product sum qty
            $this->update_sum_qty($product->id);

            return response()->json(['success' => 'The product variants has been <b class="text-success">created</b> successfully!']);
        }else{
            return response()->json(['error' => 'Selected variation already exist!']);
        }

    }

    function edit(Product_combination $product_combination){
        return view('common.product.add-variants.product-variation-form',compact('product_combination'));
    }

    function update(Request $request, Product_combination $product_combination){
        // dd($request->all());
        Session::put('temp_combination',$product_combination);
        $vo = Variation_option::where('id',$request->option_id)->first();

        $check = Product_combination::where('id','!=',$product_combination->id)
            ->where('product_id',$product_combination->product_id)->where('combination_string','LIKE', '%'.$vo->origin.'%');

        $newString = ''; $readable = array();
        foreach(explode('~',$product_combination->combination_string) as $cmbString){
            if($cmbString != $vo->origin){
                $newString .= $cmbString;   $readable[] = $cmbString;
            }
        }

        $stringParts = str_split($newString);
        sort($stringParts);

        // return response()->json(['warning' => $pvo->first()->variation_option->title]);

        $product_combination->update([
            'combination_string'=>implode('~',$readable),
            'unique_string'=>implode($stringParts)
        ]);

        //update the sum of combination tble qty = product table qty
        $this->update_sum_qty($product_combination->product_id);

        // dd($check->count());

        if($check->count() <1){
            Product_variation_option::where([
                'product_id'=>$request->product_id,
                'variation_id'=>$request->variation_id,
                'variation_option_id'=>$request->option_id
            ])->delete();
        }

        if($product_combination->combination_string==''){
            $product_combination->delete();
        }


        return response()->json(['success' => '<p class="alert alert-success"> <i class="fa fa-check text-success"></i> Variation has been updated successfully</p>']);
    }



    function update_qty(Product_combination $product_combination, $qty){
        $product_combination->update(['qty'=>$qty]);
        Product_User::create([
            'product_id'=>$product_combination->product_id,
            'product_combination_id'=>$product_combination->id,
            'user_id'=>Auth::user()->id,
            'action'=>'product-variation-update'
        ]);

        $this->update_sum_qty($product_combination->product_id);

        return response()->json(['success' => 'Variation Quantity has been updated successfully']);
    }

    function destroy(Product_combination $product_combination){

        // dd($product_combination);
        try {
            // echo $product_combination->combination_string.'<br/><br/>';

            foreach(explode('~',$product_combination->combination_string) as $cmbString){
                $getOtherCombination = Product_combination::where('product_id',$product_combination->product_id)
                    ->where('combination_string','LIKE', '%' . $cmbString . '%')->count();
                
                if($getOtherCombination ==1){
                    $vo = Variation_option::where('origin',$cmbString)->first();
                    // echo 'product_id:'.$product_combination->product_id.', variation_id: '.$vo->variation_id.', option_id: '.$vo->id.'<br/>';
                    Product_variation_option::where([
                        'product_id'=>$product_combination->product_id,
                        'variation_id'=>$vo->variation_id,
                        'variation_option_id'=>$vo->id
                    ])->delete();
                }
            }
            $product_combination->delete();

            return response()->json(['success' => 'Item hasn been removed successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. May the row is related to some other table!']);
        }
    }


    function save2product_variation_option(Request $request, Product $product){
        $allOptions = array_filter($request->all());
        $filledOptions = array(); $optionIds = array();
        unset($allOptions['_token']);
        Session::put('allOptions',$allOptions);

        foreach($allOptions as $field=>$option){

            // $variation = Variation_option::find($option);
            // $data = ['product_id'=>$product->id,'variation_id'=>$variation->variation_id,'variation_option_id'=> $option];
            // $check = Product_variation_option::where($data);
            // if($check->count() <1){
            //     Product_variation_option::create($data);
            // }

            $filledOptions[$field] = $option;
            $optionIds[] = array('id'=>$option);
        }

        $options = Variation_option::whereIn('id',$optionIds)->orderBy('title','asc')->get();
        return view('common.product.add-variants.form',compact('product','options'));
    }

    private function update_sum_qty($product_id){
        $combQty = Product_combination::where('product_id',$product_id)->sum('qty');
        Product::where('id',$product_id)->update(['qty'=>$combQty]);
    }


}
