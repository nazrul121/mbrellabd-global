<?php

namespace App\Http\Controllers;

use App\Models\Campaign_product;
use App\Models\Cartlist;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Variation_option;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class CartController extends Controller
{


    function addWishlist(Product $product){
        if(!Auth::check()) {
             $user_id = null;
        }else $user_id = Auth::user()->id;

        $check = Wishlist::where(['product_id'=>$product->id,'session_id'=>Session()->get('session_id')]);
    
        if($check->count() <1){
          
            Wishlist::create([
                'session_id'=>Session()->get('session_id'),
                'product_id'=>$product->id,
                'user_id'=> $user_id
            ]);
        }
        $data[0] = Wishlist::where(['session_id'=>Session()->get('session_id')])->count();
        $data[1] = 'success';
        return response()->json($data);
    }

    function removeWishlist(Wishlist $wishlist){
        $delete = $wishlist->delete();

        if($delete){
            $data[0] = Wishlist::where(['session_id'=>session()->get('session_id')])->count();
            $data[1] = 'success';
            return response()->json($data);
        }else{
            $data[1] = 'failed';
            return response()->json($data);
        }
    }

    function my_cart(Request $request){

        if($request->update_cart && $request->cart_id){
            // dd($request->qty);
            foreach($request->cart_id as $key=>$id){
                $cart = Cartlist::where('id',$id)->first();
                if($cart->product->qty > $request->qty[$key]){
                    Cartlist::where('id',$id)->update([ 'qty'=>$request->qty[$key] ]);
                }
            }
            $this->save_cart_to_session();  return back();
        }
        return view('my_cart');
    }

    function addTocart(Request $request, $lang){
    
        try{
            $proCombQty = Product_combination::where(['product_id'=>$request->id])->pluck('qty')->first();
           

            if( ($request->variants =='' || $request->variants==null) && $proCombQty){
                $data[1] = 'dismatch';
                $data[3] = 'Combination item is <b>Stock out</b>';
                return response()->json($data);
            }

            $itemQty = Product::where('id',$request->id)->pluck('qty')->first();
            if($request->variants !=''){
                //dd($request->all());
                $origin = '';
                foreach(explode(',',$request->variants) as $v){
                    $origin .= Variation_option::where('id',$v)->pluck('origin')->first();
                }

                $stringParts = str_split(str_replace(',','',strtolower($origin)));
                sort($stringParts);

                $checkComb = Product_combination::where(['product_id'=>$request->id, 'unique_string'=>implode($stringParts)]);

                // No match found with selected variations!
                if($checkComb->first()==null){
                    $data[1] = 'dismatch';
                    $data[3] = 'Combination item is <b>Stock out</b>';
                    return response()->json($data);
                }
                // dd(session()->get('user_currency'));
                if($checkComb->count()>1){
                    $data[1] = 'dismatch';
                    $data[3] = 'issue of duplicting the variation combinations string';
                    return response()->json($data);
                }else{
                    //match qty
                    $variant = $checkComb->first();

                    if( is_numeric($request->qty) && $request->qty <=0){
                        $data[1] = 'qty_dismatch';  $data[3] = 'Quantity must be greater then zero';
                        return response()->json($data);
                    }
                    if($variant->qty <1){
                        $data[1] = 'qty_dismatch';  $data[3] = 'Sorry! The product is <b>Stock out</b>';
                        return response()->json($data);
                    }
                    if($variant->qty < $request->qty){
                        $data[1] = 'qty_dismatch';  $data[3] = 'Sorry! You may order maximum <b>'.$variant->qty.'</b> Qty';
                        return response()->json($data);
                    }
                    
                    $itemQty = $variant->qty;
                    $cartlist = Cartlist::where(['country_id'=>session('user_currency')->id,'session_id'=>session()->get('session_id'),'product_id'=>$request->id,'product_combination_id'=>$variant->id]);
                    if ($cartlist->exists())  $cartlist->update(['qty'=>$cartlist->first()->qty + 1]);
                    else{
                        if (auth()->check()) $user_id = auth()->user()->id;else $user_id = null;
                        $data = [
                            'country_id'=>session('user_currency')->id,
                            'session_id' =>session()->get('session_id'),
                            'product_id'=>$request->id,
                            'variation_option_id'=>$request->variation_option_id,
                            'product_combination_id'=>$variant->id,
                            'campaign_id'=> Campaign_product::where('product_id',$request->id)->pluck('campaign_id')->first(),
                            'qty'=>$request->qty,
                            'user_id'=>$user_id
                        ];
                        // dd($data);
                        Cartlist::create($data);
                    }

                }
            }
            else{
                if($itemQty >0){
                    $cartlist = Cartlist::where([
                        'country_id'=>session('user_currency')->id,
                        'session_id'=>Session::get('session_id'),
                        'product_id'=>$request->id
                    ]);

                    if ($cartlist->exists())  $cartlist->update(['qty'=>$cartlist->first()->qty + 1]);
                    else{
                        if (Auth::check()) $user_id = Auth::user()->id;else $user_id = null;
                        Cartlist::create([
                            'country_id'=>session('user_currency')->id,
                            'session_id' =>session()->get('session_id'),'product_id'=>$request->id,
                            'variation_option_id'=>$request->variation_option_id,
                            'campaign_id'=> Campaign_product::where('product_id',$request->id)->pluck('campaign_id')->first(),
                            'qty'=>$request->qty, 'user_id'=>$user_id
                        ]);
                    }
                }

            }

            if($itemQty <1){
                $data[1] = 'dismatch';  $data[3] = '<b>Stock alert</b>: The item is Out of stock';
            }else{
                $this->save_cart_to_session();
                $data[0] = Session::get('cart')->count(); $data[1] = 'success';
            }
            return response()->json($data);

        } catch (Throwable $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', "Something went wrong. Product can not be added to cart.");
        }
    }

    function my_cart_ajax(){
        return view('includes.cart-ajax');
    }

    private function save_cart_to_session(){
        $cart = Cartlist::where(['country_id'=> session()->get('user_currency')->id, 
            'session_id'=> session()->get('session_id'),'status'=>'1'])->get();
        session()->put('cart',$cart);
        session()->put('cartNum',session()->get('cart')->where('country_id',session('user_currency')->id)->count());
    }

    function remove_cart($key=null, $id=null){
        unset(session()->get('cart')[$key]);

        Cartlist::where('id',$id)->delete();
        $this->save_cart_to_session(); $price = array();
        foreach(session()->get('cart') as $cart){
            $price[] = $cart->qty * product_price($cart->product_id, $cart->product->sale_price);
        }

        $data[0] = session()->get('cart')->count();
        $data[1] = array_sum($price);
        return response()->json($data);
    }

    function check_product_stock(Request $request, Product $product){
        $origin = '';
        foreach(explode(',',$request->variants) as $v){
            $origin .= Variation_option::where('id',$v)->pluck('origin')->first();
        }

        $stringParts = str_split(str_replace(',','',strtolower($origin)));
        sort($stringParts);

        $checkComb = Product_combination::where(['product_id'=>$product->id, 'unique_string'=>implode($stringParts)]);

        // echo implode($stringParts);

        if($checkComb->first()==null || $checkComb->first()->qty <1){
            echo '<b class="text-danger">Out of stock</b>';
        }else echo '<span class="text-white">In stock</span>';
    }


}
