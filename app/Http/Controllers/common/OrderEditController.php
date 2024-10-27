<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Order_item;
use App\Models\Order_item_edition;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Shipping_address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderEditController extends Controller
{
    function edit_order(Order $order, Request $request){
        if($request->draw){

            return datatables()::of(Order_item::where('order_id',$order->id)->where('status','!=','removed')->orderBy('id'))
            ->addIndexColumn()
            ->editColumn('product_info', function ($order_item) {
                $data = '<h6 class="p-1"><img src="'.$order_item->product->thumbs.'" style="height:50px"> '.$order_item->id.'. '.$order_item->product->title;

                if($order_item->product_combination_id !=null){
                    $data .= '<button class="btn btn-info float-right updateComb'.$order_item->id.' updateComButton" data-id="'.$order_item->id.'" disabled>Update</button>
                    <select class="float-right btn table-bordered text-left changeCombination comb'.$order_item->id.'" >
                        <option value="">change variations</option>';
                        $variations = $order_item->product->product_combinations()->get();
                        foreach ($variations as $comb){
                            if($comb->id==$order_item->product_combination_id){
                                $selected = 'selected';
                            }else $selected = '';

                            $data .= '<option '.$selected.' value="'.$comb->id.'|'.$order_item->id.'">';

                            foreach (explode('~',$comb->combination_string) as $string){
                                $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();
                                if($v !=null){
                                    $data .= $v->variation->title.': '.$v->title.' ';
                                }
                            }
                            $data .='</option>';
                        }
                    $data .= '</select> </h6>';
                }
                return $data;
            })
            ->editColumn('price', function($order_item) use ($order){
                $salePrice = $order_item->sale_price * $order_item->qty;
                $discountPrice = $order_item->discount_price * $order_item->qty;

                $data = ' Regular Price: <span style="text-decoration:line-through">'.$order->country->currencySymbol.$salePrice.'</span> <br>
                Discount Price:'.$order->country->currencySymbol.$discountPrice.' <br>';

                return $data;
            })
            ->editColumn('qty', function($order_item){
                $data = '<input type="text" min="1" class="p-2 changeQty qty'.$order_item->id.'" data-id="'.$order_item->id.'" value="'.$order_item->qty.'">
                <button class="btn btn-sm btn-danger float-right mr-0 mt-2 removeBtn" data-id="'.$order_item->id.'">Remove the item</button>';
                return $data;
            })
            ->rawColumns(['product_info','price','qty'])->make(true);
        }
        return view('common.order.edit.index', compact('order'));
    }

    function update_combination($type, Order_item $order_item, Product_combination $product_combination){
        Order_item_edition::create([
            'type'=>$type, 'order_item_id'=>$order_item->id,
            'product_id'=>$order_item->product_id,
            'product_combination_id'=>$order_item->product_combination_id,
            'user_id'=>Auth::user()->id
        ]);
        $order_item->update(['product_combination_id'=>$product_combination->id]);
    }

    function update_order_qty($type, Order_item $order_item, $qty){
        
        Order_item_edition::create([
            'type'=>$type,'order_item_id'=>$order_item->id,
            'product_id'=>$order_item->product_id,
            'product_combination_id'=>$order_item->product_combination_id,
            'qty'=>$order_item->qty,  'user_id'=>Auth::user()->id
        ]);
        $order_item->update(['qty'=>$qty]);

        $this->re_arrage_order($order_item->order_id);
    }

    function delete_order_item($type, Order_item $order_item){
        // dd($order_item);
        Order_item_edition::create([
            'type'=>$type,'order_item_id'=>$order_item->id,
            'product_id'=>$order_item->product_id,
            'product_combination_id'=>$order_item->product_combination_id,
            'user_id'=>Auth::user()->id
        ]);

        $this->re_arrage_order($order_item->order_id);

        $order_item->update(['status'=>'removed']);
    }



    // product id wise variations, ajax call
    function combinations(Product $product){
        // return Product_combination::where('product_id',$product->id)->get();
        foreach( Product_combination::where('product_id',$product->id)->get() as $comb){
            echo '<option value="'.$comb->id.'">';
            foreach (explode('~',$comb->combination_string) as $vo){
                $v = \App\Models\Variation_option::where('origin',$vo)->select('title','variation_id')->first();
                echo $v->variation->title.': '.$v->title.' ';
            }
            echo '</option>';
        }

    }

    function add_product(Order $order, Request $request){
        // dd($request->all());

        $product = Product::where('id',$request->product_id)->first();
        $p = DB::table('product_promotion')->where(['product_id'=>$product->id, 'status'=>'1'])->first();

        $newPrice = '';
        // dd(\Session::get('user_currency')->name);
        if(strtolower($order->country->short_name)=='bgd'){
            if($p==null) $newPrice = $product->sale_price;
            else $newPrice = $p->discount_price;
        }else{
            if($p==null) $newPrice =$product->sale_price;
            else  $newPrice = $p->discount_price;

            return $newPrice / $order->country->currencyValue;
        }


        $check = Order_item::where(['order_id'=>$order->id,'product_id'=>$request->product_id,'product_combination_id'=>$request->variation,'status'=>'placed']);
        if($check->count() < 1){
            $order_item = Order_item::create([
                'order_id'=>$order->id, 'product_id'=>$request->product_id,
                'product_combination_id'=>$request->variation,
                'qty'=>$request->qty, 'net_price'=>$product->net_price,
                'sale_price'=>$product->sale_price, 'discount_price'=>$newPrice,
                'vat'=>$product->vat, 'vat_type'=>$product->vat_type
            ]);

            Order_item_edition::create([
                'type'=>'added-new-item',  'order_item_id'=>$order_item->id,
                'product_id'=>$request->product_id, 'product_combination_id'=>$request->variation,
                'qty'=>$request->qty, 'user_id'=>Auth::user()->id
            ]);

            $this->re_arrage_order($order->id);

            return response()->json(['success'=>'The item has been added into to order successfully ']);
        }else return response()->json(['failed'=>'The item already exist into the list!!']);
    }


    function edit_address(Order $order, Request $request){
        // dd($request->all(), explode('|',$request->division)[1]);
        if($request->type=='billing'){
            $data = [
                'division'=>explode('|',$request->division)[1],	'district'=>explode('|',$request->district)[1] ,'city'=>explode('|',$request->city)[1],
                'first_name'=>$request->fname, 'last_name'=>$request->lname,
                'address'=>$request->address
            ];
            Order::where('id',$order->id)->update($data);

            // $data = [
            //     'division_id'=>$request->division,	'district_id'=>$request->district,'city_id'=>$request->city,
            //     'first_name'=>$request->fname, 'last_name'=>$request->lname,
            //     'address'=>$request->address
            // ];
            // Customer::where('id',$request->id)->update($data);
        }else{
            $data = [
                'ship_division'=>explode('|',$request->shipping_division)[1],	'ship_district'=>explode('|',$request->shipping_district)[1] ,'ship_city'=>explode('|',$request->shipping_city)[1],
                'ship_first_name'=>$request->shipping_fname, 'ship_last_name'=>$request->shipping_lname,
                'ship_address'=>$request->shipping_address
            ];
            Order::where('id',$order->id)->update($data);

            $zone = \App\Models\City_zone::where('city_id',explode('|',$request->shipping_city)[0])->first();
            if($zone !=null){
                $deliveryCost =  $zone->zone->delivery_cost;
                $order->update([ 'shipping_cost'=>$deliveryCost ]);
            }
        }
        return back();
    }

    private function re_arrage_order($order_id){
        $order = Order::find($order_id);
        // dd($order);
        $orderItems =  Order_item::where(['order_id'=>$order_id,'status'=>'placed']);
        $total = array();

        foreach($orderItems->get() as $order_item){
            $total[] = $order_item->discount_price * $order_item->qty;
            echo 'item price: '.$order_item->discount_price; echo '<br/>';
        }
        $order->update(['total_items'=>$orderItems->count(), 'total_cost'=>array_sum($total)]);
    }




}
